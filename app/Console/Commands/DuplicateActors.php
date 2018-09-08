<?php

namespace App\Console\Commands;

use App\Actor;
use App\MovieHasActor;
use function foo\func;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;
use Tmdb\Repository\MovieRepository;

class DuplicateActors extends Command
{
    protected $signature = 'delete:duplicate:actors';
    protected $description = 'Command description';
    private $movieDb;

    public function __construct(MovieRepository $movieDb)
    {
        parent::__construct();
        $this->movieDb = $movieDb;
    }

    public function handle()
    {
        // find all actors without tmdb_id
        $actors = Actor::distinct('name')->orderBy('tmdb_id', 'desc')->get();
        // group by name
        $actors = $actors->groupBy('name');

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%]   %percent%% -- %message%');
        $bar = $this->output->createProgressBar(count($actors));
        $bar->setFormat('custom');

        // find and set tmdb_id for only one actor with duplicate names
        // e.g. 3 actors have the same name but no tmdb_id -> set tmdb_id
        foreach ($actors as $actor) {
            $actor = $actor[0];
            $bar->setMessage($actor->name);
            if ($actor->tmdb_id) {
                $bar->advance();
                continue;
            }
            $res = $this->movieDb->getApi()->get('search/person', ['query' => $actor->name]);
            if (count($res['results']) > 0) {
                $actor->tmdb_id = $res['results'][0]['id'];
                $actor->save();
            } else {
                $this->warn(' No results.');
                // TODO delete duplicate movie_has_actor rows
            }
            $bar->advance();
        }

        MovieHasActor::whereHas('actor', function ($query) {
            $query->whereNull('tmdb_id');
        })->delete();

        Actor::whereNull('tmdb_id')->delete();
    }
}
