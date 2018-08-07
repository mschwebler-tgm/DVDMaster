<?php

namespace App\Console\Commands;

use App\Actor;
use App\Genre;
use App\Movie;
use App\MovieHasActor;
use App\MovieHasGenre;
use App\Service\Dao\MovieDao;
use Illuminate\Console\Command;
use Tmdb\Repository\MovieRepository;

class ImportMovie extends Command
{
    protected $signature = 'import:movie {tmdbId}';
    protected $description = 'Import movie from tmdb. Also adds relations to genres and actors.';

    private $movieDb;
    private $movieDao;

    public function __construct(MovieRepository $movieDb,  MovieDao $movieDao)
    {
        parent::__construct();
        $this->movieDb = $movieDb;
        $this->movieDao = $movieDao;
    }

    public function handle()
    {
        $tmdbId = $this->argument('tmdbId');
        $movie = Movie::where('tmdb_id', $tmdbId)->first();
        if ($movie) {
            $this->info('Movie already in database. Updating...');
        } else {
            $this->info('Creating new movie');
            $movie = new Movie();
        }

        /** @var \Tmdb\Model\Movie|\Tmdb\Model\AbstractModel $movieRes */
        $movieRes = $this->movieDb->load($tmdbId, ['append_to_response' => 'credits', 'language' => 'de']);
        $this->setMovieAttrs($movie, $movieRes);
        $movie->save();

        /** @var \Tmdb\Model\Person\CastMember $actor */
        foreach ($movieRes->getCredits()->getCast() as $actor) {
            $dbActor = Actor::where('tmdb_id', $actor->getId())->first();
            if (!$dbActor || $this->actorNotComplete($dbActor)) {
                $this->info('dispatching ImportActor for ' . $actor->getName());
                \App\Jobs\ImportActor::dispatch($actor->getId(), $movie->tmdb_id);
            } else {
                MovieHasActor::firstOrCreate([
                    'actor_id' => $dbActor->id,
                    'movie_id' => $movie->id
                ]);
            }
        }

        /** @var \Tmdb\Model\Genre $genre */
        foreach ($movieRes->getGenres() as $genre) {
            $dbGenre = Genre::firstOrCreate([
                'tmdb_id' => $genre->getId(),
                'name' => $genre->getName()
            ]);
            MovieHasGenre::firstOrCreate([
                'genre_id' => $dbGenre->id,
                'movie_id' => $movie->id
            ]);
        }
    }

    private function setMovieAttrs(&$movie, $movieRes)
    {
        $movie->tmdb_id = $movieRes->getId() ?: null;
        $movie->imdb_id = $movieRes->getImdbId() ?: null;
        $movie->backdrop_path = $movieRes->getBackdropImage() ? $movieRes->getBackdropImage()->getFilePath() : null;
        $movie->poster_path = $movieRes->getPosterPath() ?: null;
        $movie->belongs_to_collection = (bool)$movieRes->getBelongsToCollection() ?: null;
        $movie->budget = $movieRes->getBudget() ?: null;
        $movie->homepage = $movieRes->getHomepage() ?: null;
        $movie->original_title = $movieRes->getOriginalTitle() ?: null;
        $movie->overview = $movieRes->getOverview() ?: null;
        $movie->popularity = $movieRes->getPopularity() ?: null;
        $movie->release_date = $movieRes->getReleaseDate() ?: null;
        $movie->revenue = $movieRes->getRevenue() ?: null;
        $movie->tagline = $movieRes->getTagline() ?: null;
        $movie->title = $movieRes->getTitle() ?: null;
        $movie->vote_average = $movieRes->getVoteAverage() ?: null;
        $movie->vote_count = $movieRes->getVoteCount() ?: null;
        $movie->duration = $movieRes->getRuntime() ?: null;
    }

    private function actorNotComplete(Actor $actor)
    {
        return !$actor->biography ||
               !$actor->birthday ||
               !$actor->gender ||
               !$actor->popularity ||
               !$actor->profile_path;
    }

}