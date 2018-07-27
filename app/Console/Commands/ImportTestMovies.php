<?php

namespace App\Console\Commands;

use App\Actor;
use App\MovieHasActor;
use App\MovieHasGenre;
use App\Service\Dao\MovieDao;
use Illuminate\Console\Command;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Model\Genre;
use Tmdb\Repository\MovieRepository;

class ImportTestMovies extends Command
{
    const PAGES_TO_IMPORT = 10;  // 20 items per page

    protected $signature = 'import:testMovies';
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
        $this->info('Importing movies...');
        for ($i = 1; $i <= self::PAGES_TO_IMPORT; $i++) {
            /** @var ResultCollection $movies */
            $movies = $this->movieDb->getPopular(['page' => $i, 'language' => 'de']);
            /** @var \Tmdb\Model\Movie $movie */
            foreach ($movies->toArray() as $movie) {
                $movie = $this->movieDb->load($movie->getId(), ['append_to_response' => 'credits', 'language' => 'de']);
                $dbMovie = $this->movieDao->insertFromTmdb($movie);
                $this->addCast($dbMovie, $movie->getCredits()->getCast());

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

    private function addCast($dbMovie, $cast)
    {
        /** @var \Tmdb\Model\Person\CastMember $actor */
        foreach ($cast as $actor) {
            $dbActor = Actor::where('tmdb_id', $actor->getId())->first();

            if (!$dbActor) {
                $dbActor = new Actor();
                $dbActor->tmdb_id = $actor->getId();
                $dbActor->name = $actor->getName();
                $dbActor->profile_path = $actor->getProfilePath();
                $dbActor->save();
            }

            MovieHasActor::firstOrCreate([
                'movie_id' => $dbMovie->id,
                'actor_id' => $dbActor->id
            ]);
        }
    }
}
