<?php

namespace App\Service\Dao;

use App\Genre;
use App\Movie;
use App\MovieHasGenre;
use App\Rental;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MovieDao
{
    /**
     * @param $movie \Tmdb\Model\Movie|\Tmdb\Model\AbstractModel
     * @return Movie
     */
    public function insertFromTmdb($movie)
    {
        $dbMovie = Movie::where('tmdb_id', $movie->getId())->first();
        if ($dbMovie) {
            return $dbMovie;
        }

        $dbMovie = new Movie();
        $dbMovie->tmdb_id = $movie->getId() ?: null;
        $dbMovie->imdb_id = $movie->getImdbId() ?: null;
        $dbMovie->backdrop_path = $movie->getBackdropImage() ? $movie->getBackdropImage()->getFilePath() : null;
        $dbMovie->poster_path = $movie->getPosterPath() ?: null;
        $dbMovie->belongs_to_collection = (bool)$movie->getBelongsToCollection() ?: null;
        $dbMovie->budget = $movie->getBudget() ?: null;
        $dbMovie->homepage = $movie->getHomepage() ?: null;
        $dbMovie->original_title = $movie->getOriginalTitle() ?: null;
        $dbMovie->overview = $movie->getOverview() ?: null;
        $dbMovie->popularity = $movie->getPopularity() ?: null;
        $dbMovie->release_date = $movie->getReleaseDate() ?: null;
        $dbMovie->revenue = $movie->getRevenue() ?: null;
        $dbMovie->tagline = $movie->getTagline() ?: null;
        $dbMovie->title = $movie->getTitle() ?: null;
        $dbMovie->vote_average = $movie->getVoteAverage() ?: null;
        $dbMovie->vote_count = $movie->getVoteCount() ?: null;
        $dbMovie->duration = $movie->getRuntime() ?: null;
        $dbMovie->save();

        return $dbMovie;
    }

    public function insertFromArray($movieArray)
    {
        $movie = Movie::where('tmdb_id', $movieArray['id'])->first();
        if ($movie) {
            return $movie;
        }

        if (!isset($movieArray['title']) && !isset($movieArray['name'])) {
            return null;
        }

        $movie = Movie::create([
            "vote_average" => isset($movieArray['vote_average']) && $movieArray['vote_average'] ? $movieArray['vote_average'] : null,
            "vote_count" => isset($movieArray['vote_count']) && $movieArray['vote_count'] ? $movieArray['vote_count'] : null,
            "custom_rating" => isset($movieArray['custom_rating']) && $movieArray['custom_rating'] ? $movieArray['custom_rating'] : null,
            "tmdb_id" => isset($movieArray['id']) && $movieArray['id'] ? $movieArray['id'] : null,
            "imdb_id" => isset($movieArray['imdb_id']) && $movieArray['imdb_id'] ? $movieArray['imdb_id'] : null,
            "title" => isset($movieArray['title']) && $movieArray['title'] ? $movieArray['title'] : $movieArray['name'],
            "popularity" => isset($movieArray['popularity']) && $movieArray['popularity'] ? $movieArray['popularity'] : null,
            "original_title" => isset($movieArray['original_title']) && $movieArray['original_title'] ? $movieArray['original_title'] : null,
            "backdrop_path" => isset($movieArray['backdrop_path']) && $movieArray['backdrop_path'] ? $movieArray['backdrop_path'] : null,
            "poster_path" => isset($movieArray['poster_path']) && $movieArray['poster_path'] ? $movieArray['poster_path'] : null,
            "overview" => isset($movieArray['overview']) && $movieArray['overview'] ? $movieArray['overview'] : null,
            "release_date" => isset($movieArray['release_date']) && $movieArray['release_date'] ? $movieArray['release_date'] : null,
            "belongs_to_collection" => isset($movieArray['belongs_to_collection']) && $movieArray['belongs_to_collection'] ? $movieArray['belongs_to_collection'] : null,
            "budget" => isset($movieArray['budget']) && $movieArray['budget'] ? $movieArray['budget'] : null,
            "homepage" => isset($movieArray['homepage']) && $movieArray['homepage'] ? $movieArray['homepage'] : null,
            "revenue" => isset($movieArray['revenue']) && $movieArray['revenue'] ? $movieArray['revenue'] : null,
            "tagline" => isset($movieArray['tagline']) && $movieArray['tagline'] ? $movieArray['tagline'] : null
        ]);

        if (isset($movieArray['genre_ids'])) {
            foreach ($movieArray['genre_ids'] as $genre_id) {
                $genre = Genre::where('tmdb_id', $genre_id)->first();
                if (!$genre) { continue; }
                MovieHasGenre::create([
                    'movie_id' => $movie->id,
                    'genre_id' => $genre->id
                ]);
            }
        }

        return $movie;
    }

    public function insertFromCustomArray($payload, $posterPath = null, $backdropPath = null)
    {
        if (!isset($payload['movie'])) {
            return;
        }

        $movie = (array) json_decode($payload['movie']);

        $movie = Movie::create([
            "custom_rating" => isset($movie['custom_rating']) ? $movie['custom_rating'] : null,
            "title" => isset($movie['title']) ? $movie['title'] : null,
            "backdrop_path" => $backdropPath ? Storage::url($backdropPath) : null,
            "poster_path" => $backdropPath ? Storage::url($posterPath) : null,
            "overview" => isset($movie['overview']) ? $movie['overview'] : null,
            "release_date" => isset($movie['release_date']) ? $movie['release_date'] : null,
        ]);

        return $movie;
    }

    public function rent(Movie $movie, User $user)
    {
        /** @var Rental $rentedBy */
        $rentedBy = $movie->rentedBy()->where('state', '!=', 'complete')->first();
        if ($rentedBy) {
            /** @var Rental $rental */
            $rental = Rental::where([['movie_id', '=', $movie->id], ['state', '=', 'pending']])->first();
            $rental->state = 'complete';
            $rental->save();
        }
        $movie->rentedBy()->attach($user->id);
        /** @var Rental $rental */
        $rental = Rental::where([['movie_id', '=', $movie->id], ['user_id', '=', $user->id], ['state', '=', 'pending']])->first();
        $rental->setCreatedAt(Carbon::now());
        $rental->save();
        return $rental;
    }

    public function retrieve(Movie $movie, $like, $date, $quality)
    {
        if (!$date) {
            $date = Carbon::now();
        }

        $rental = $movie->pendingRental()->first();
        if (!$rental) {
            return null;
        }
        $rental->like = $like;
        $rental->retrieved_at = $date;
        $rental->quality = $quality;
        $rental->state = 'complete';
        $rental->save();
        return $rental;
    }

    /**
     * @param Movie $movie
     * @param array $genres
     */
    public function updateGenres(Movie $movie, $genres)
    {
        $genresToDetach = $movie->genres->pluck('id')->toArray();
        $genresToAttach = [];
        foreach ($genres as $key => $genre) {
            if (!in_array($genre['id'], $genresToDetach)) {
                $genresToAttach[] = $genre['id'];
            } else {
                unset($genresToDetach[$key]);
            }
        }
        $movie->genres()->attach($genresToAttach);
        $movie->genres()->detach($genresToDetach);
    }

    /**
     * @param Movie $movie
     * @param array $actors
     */
    public function updateActors(Movie $movie, $actors)
    {
        $actorsToDetach = $movie->actors->pluck('id')->toArray();
        $actorsToAttach = [];

        foreach ($actors as $key => $actor) {
            if (!in_array($actor['id'], $actorsToDetach)) {
                $actorsToAttach[] = $actor['id'];
            } else {
                unset($actorsToDetach[$key]);
            }
        }
        $movie->actors()->attach($actorsToAttach);
        $movie->actors()->detach($actorsToDetach);
    }
}