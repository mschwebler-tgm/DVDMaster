<?php

namespace App\Console\Commands;

use App\Actor;
use App\Movie;
use App\MovieHasActor;
use Illuminate\Console\Command;
use Tmdb\Repository\MovieRepository;

class ImportActor extends Command
{
    protected $signature = 'import:actor {tmdbId}';
    protected $description = 'Command description';

    private $movieDb;

    public function __construct(MovieRepository $movieDb)
    {
        parent::__construct();
        $this->movieDb = $movieDb;
    }

    public function handle()
    {
        $tmdbId = $this->argument('tmdbId');
        $actor = Actor::where('tmdb_id', $tmdbId)->first();
        if ($actor) {
            $this->info('Actor already in database. Updating...');
        } else {
            $this->info('Creating new actor');
            $actor = new Actor();
        }

        try {
            $actorRes = $this->movieDb->getApi()->get("person/$tmdbId", ['language' => 'de']);
            $actor = $this->setActorAttrs($actor, $actorRes);
            $actor->save();

            // get know_for
            $knownForRes = $this->movieDb->getApi()->get("person/$tmdbId/combined_credits", ['language' => 'de']);
            foreach ($knownForRes['cast'] as $knownFor) {
                $dbMovie = Movie::where('tmdb_id', $knownFor['id'])->first();
                if ($dbMovie) {
                    MovieHasActor::firstOrCreate([
                        'actor_id' => $actor->id,
                        'movie_id' => $dbMovie->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function setActorAttrs(Actor $actor, $actorRes)
    {
        $actor->birthday = $actorRes['birthday'] ? $actorRes['birthday'] : null;
        $actor->deathday = $actorRes['deathday'] ? $actorRes['deathday'] : null;
        $actor->imdb_id = $actorRes['imdb_id'] ? $actorRes['imdb_id'] : null;
        $actor->name = $actorRes['name'] ? $actorRes['name'] : null;
        $actor->also_known_as = $actorRes['also_known_as'] && count($actorRes['also_known_as']) > 0 ? $actorRes['also_known_as'][0] : null;
        $actor->gender = $actorRes['gender'] ? $actorRes['gender'] : null;
        $actor->biography = $actorRes['biography'] ? $actorRes['biography'] : null;
        $actor->popularity = $actorRes['popularity'] ? $actorRes['popularity'] : null;
        $actor->place_of_birth = $actorRes['place_of_birth'] ? $actorRes['place_of_birth'] : null;
        $actor->profile_path = $actorRes['profile_path'] ? $actorRes['profile_path'] : null;
        $actor->adult = $actorRes['adult'] ? $actorRes['adult'] : 0;
        $actor->homepage = $actorRes['homepage'] ? $actorRes['homepage'] : null;
        return $actor;
    }
}