<?php

namespace App\Service\Dao;

use App\Movie;

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
        $dbMovie->tmdb_id = $movie->getId();
        $dbMovie->imdb_id = $movie->getImdbId();
        $dbMovie->backdrop_path = $movie->getBackdropImage();
        $dbMovie->belongs_to_collection = $movie->getBelongsToCollection();
        $dbMovie->budget = $movie->getBudget();
        $dbMovie->homepage = $movie->getHomepage();
        $dbMovie->original_title = $movie->getOriginalTitle();
        $dbMovie->overview = $movie->getOverview();
        $dbMovie->popularity = $movie->getPopularity();
        $dbMovie->release_date = $movie->getReleaseDate();
        $dbMovie->revenue = $movie->getRevenue();
        $dbMovie->tagline = $movie->getTagline();
        $dbMovie->title = $movie->getTitle();
        $dbMovie->vote_average = $movie->getVoteAverage();
        $dbMovie->vote_count = $movie->getVoteCount();
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
            "vote_average" => isset($movieArray['vote_average']) ? $movieArray['vote_average'] : null,
            "vote_count" => isset($movieArray['vote_count']) ? $movieArray['vote_count'] : null,
            "tmdb_id" => isset($movieArray['id']) ? $movieArray['id'] : null,
            "imdb_id" => isset($movieArray['imdb_id']) ? $movieArray['imdb_id'] : null,
            "title" => isset($movieArray['title']) ? $movieArray['title'] : $movieArray['name'],
            "popularity" => isset($movieArray['popularity']) ? $movieArray['popularity'] : null,
            "original_title" => isset($movieArray['original_title']) ? $movieArray['original_title'] : null,
            "backdrop_path" => isset($movieArray['backdrop_path']) ? $movieArray['backdrop_path'] : null,
            "overview" => isset($movieArray['overview']) ? $movieArray['overview'] : null,
            "release_date" => isset($movieArray['release_date']) ? $movieArray['release_date'] : null,
            "belongs_to_collection" => isset($movieArray['belongs_to_collection']) ? $movieArray['belongs_to_collection'] : null,
            "budget" => isset($movieArray['budget']) ? $movieArray['budget'] : null,
            "homepage" => isset($movieArray['homepage']) ? $movieArray['homepage'] : null,
            "revenue" => isset($movieArray['revenue']) ? $movieArray['revenue'] : null,
            "tagline" => isset($movieArray['tagline']) ? $movieArray['tagline'] : null
        ]);

        return $movie;
    }
}