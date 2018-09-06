<?php

namespace App\Console\Commands;

use App\Actor;
use App\Genre;
use App\Series;
use App\SeriesHasActor;
use App\SeriesHasGenre;
use Illuminate\Console\Command;
use Tmdb\Repository\TvRepository;

class ImportSeries extends Command
{
    protected $signature = 'import:series {tmdbId} {seasonsToExclude}';
    protected $description = 'Import series from tmdb. Also adds relations to genres and actors.';
    private $seriesDb;

    public function __construct(TvRepository $seriesDb)
    {
        parent::__construct();
        $this->seriesDb = $seriesDb;
    }

    public function handle()
    {
        $tmdbId = $this->argument('tmdbId');
        $series = Series::where('tmdb_id', $tmdbId)->first();
        if ($series) {
            $this->info('Movie already in database. Updating...');
        } else {
            $this->info('Creating new series');
            $series = new Series();
        }

        /** @var \Tmdb\Model\Tv|null|\Tmdb\Model\AbstractModel $seriesRes */
        $seriesRes = $this->seriesDb->load($tmdbId, ['append_to_response' => 'credits', 'language' => 'de']);
        $this->setSeriesAttrs($series, $seriesRes);
        $series->save();

        /** @var \Tmdb\Model\Person\CastMember $actor */
        foreach ($seriesRes->getCredits()->getCast() as $actor) {
            $dbActor = Actor::where('tmdb_id', $actor->getId())->first();
            if (!$dbActor || $this->actorNotComplete($dbActor)) {
                $this->info('dispatching ImportActor for ' . $actor->getName());
                \App\Jobs\ImportActor::dispatch($actor->getId(), 'Series', $series->tmdb_id);
            } else {
                SeriesHasActor::firstOrCreate([
                    'actor_id' => $dbActor->id,
                    'series_id' => $series->id
                ]);
            }
        }

        /** @var \Tmdb\Model\Genre $genre */
        foreach ($seriesRes->getGenres() as $genre) {
            $dbGenre = Genre::firstOrCreate([
                'tmdb_id' => $genre->getId(),
                'name' => $genre->getName()
            ]);
            SeriesHasGenre::firstOrCreate([
                'genre_id' => $dbGenre->id,
                'series_id' => $series->id
            ]);
        }
    }

    /**
     * @param $series
     * @param $seriesRes \Tmdb\Model\Tv
     */
    private function setSeriesAttrs(&$series, $seriesRes)
    {
        $series->name = $seriesRes->getName();
        $series->original_name = $seriesRes->getOriginalName();
        $series->tmdb_id = $seriesRes->getId();
        $series->overview = $seriesRes->getOverview();
        $series->type = $seriesRes->getType();
        $series->number_of_seasons = $seriesRes->getNumberOfSeasons();
        $series->number_of_episodes = $seriesRes->getNumberOfEpisodes();
        $series->homepage = $seriesRes->getHomepage();
        $series->popularity = $seriesRes->getPopularity();
        $series->first_air_date = $seriesRes->getFirstAirDate();
        $series->vote_average = $seriesRes->getVoteAverage();
        $series->vote_count = $seriesRes->getVoteCount();
        $series->backdrop_path = $seriesRes->getBackdropPath();
        $series->poster_path = $seriesRes->getPosterPath();
        $series->episode_runtime = count($seriesRes->getEpisodeRunTime()) > 0 ? $seriesRes->getEpisodeRunTime()[0] : null;
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
