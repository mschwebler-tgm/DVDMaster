<?php

namespace App\Service\Dao;

use App\Genre;
use App\Movie;
use App\MovieHasGenre;

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
//            $movie->genres()->attach($movieArray['genre_ids']);
            foreach ($movieArray['genre_ids'] as $genre_id) {
                MovieHasGenre::create([
                    'movie_id' => $movie->id,
                    'genre_id' => Genre::where('tmdb_id', $genre_id)->first()->id
                ]);
//                $movie->genres()->attach($genre_id);
            }
        }

        return $movie;
    }
}