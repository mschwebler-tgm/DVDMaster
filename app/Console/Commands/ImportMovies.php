<?php

namespace App\Console\Commands;

use App\MovieHasGenre;
use App\Service\Dao\MovieDao;
use Illuminate\Console\Command;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Model\Genre;
use Tmdb\Repository\MovieRepository;

class ImportMovies extends Command
{
    const PAGES_TO_IMPORT = 10;  // 20 items per page

    protected $signature = 'import:movies';
    protected $description = 'Import popular movies from themoviedb';

    private $movieDb;
    private $movieDao;

    public function __construct(MovieRepository $movieDb, MovieDao $movieDao)
    {
        parent::__construct();
        $this->movieDb = $movieDb;
        $this->movieDao = $movieDao;
    }

    public function handle()
    {
        for ($i = 1; $i <= self::PAGES_TO_IMPORT; $i++) {
            /** @var ResultCollection $movies */
            $movies = $this->movieDb->getPopular(['page' => $i]);
            /** @var \Tmdb\Model\Movie $movie */
            foreach ($movies->toArray() as $movie) {
                $dbMovie = $this->movieDao->insertFromTmdb($movie);

                foreach ($this->getGenreIds($movie) as $genreId) {
                    MovieHasGenre::firstOrCreate([
                        'movie_id' => $dbMovie->id,
                        'genre_id' => \App\Genre::where('tmdb_id', $genreId)->first()->id
                    ]);
                }
            }
        }
    }

    private function getGenreIds(\Tmdb\Model\Movie $movie)
    {
        $ids = [];
        /** @var Genre $genre */
        foreach ($movie->getGenres()->toArray() as $genre) {
            array_push($ids, $genre->getId());
        }
        return $ids;
    }
}
