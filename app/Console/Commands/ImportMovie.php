<?php

namespace App\Console\Commands;

use App\Movie;
use App\Service\Dao\MovieDao;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
        $movieRes = $this->movieDb->load($tmdbId, ['append_to_response' => ['credits'], 'language' => 'de']);
        $this->setMovieAttrs($movie, $movieRes);

        /** @var \Tmdb\Model\Person\CastMember $actor */
        foreach ($movieRes->getCredits()->getCast() as $actor) {
            Artisan::call('import:actor', ['tmdbId' => $actor->getId()]);
        }
    }

    private function setMovieAttrs(&$movie, $movieRes)
    {
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

}