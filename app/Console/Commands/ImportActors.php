<?php

namespace App\Console\Commands;

use App\Movie;
use App\MovieHasActor;
use App\Service\Dao\ActorDao;
use App\Service\Dao\MovieDao;
use Illuminate\Console\Command;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\MovieRepository;

class ImportActors extends Command
{
    const PAGES_TO_IMPORT = 10;  // 20 items per page

    protected $signature = 'import:actors';
    protected $description = 'Command description';
    private $movieDb;
    private $movieDao;
    private $actorDao;

    public function __construct(MovieRepository $movieDb, MovieDao $movieDao, ActorDao $actorDao)
    {
        parent::__construct();
        $this->movieDb = $movieDb;
        $this->movieDao = $movieDao;
        $this->actorDao = $actorDao;
    }

    public function handle()
    {
        $this->info('Importing actors...');
        for ($i = 1; $i <= self::PAGES_TO_IMPORT; $i++) {
            /** @var ResultCollection $movies */
            $people = $this->movieDb->getApi()->get('person/popular', ['page' => $i, 'language' => 'de'])['results'];
            foreach ($people as $person) {
                $personDetails = $this->movieDb->getApi()->get('person/' . $person['id'], ['language' => 'de']);
                $dbPerson = $this->actorDao->insertFromArray($personDetails);

                foreach ($person['known_for'] as $mov) {
                    $dbMovie = Movie::where('tmdb_id', $mov['id'])->first();
                    if ($dbMovie) {
                        MovieHasActor::firstOrCreate([
                            'actor_id' => $dbPerson->id,
                            'movie_id' => $dbMovie->id
                        ]);
                        continue;
                    }
                    $dbMovie = $this->movieDao->insertFromArray($mov);
                    MovieHasActor::firstOrCreate([
                        'actor_id' => $dbPerson->id,
                        'movie_id' => $dbMovie->id
                    ]);
                }
            }
        }
    }
}
