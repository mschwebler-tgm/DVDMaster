<?php

namespace App\Console\Commands;

use App\Genre;
use Illuminate\Console\Command;
use Tmdb\Repository\MovieRepository;

class ImportGenres extends Command
{
    protected $signature = 'import:genres';
    protected $description = 'Import genres from themoviedb';

    private $movieDb;

    public function __construct(MovieRepository $movieDb)
    {
        parent::__construct();
        $this->movieDb = $movieDb;
    }

    public function handle()
    {
        $res = $this->movieDb->getApi()->get('genre/movie/list');
        if (!isset($res['genres'])) {
            return;
        }

        foreach ($res['genres'] as $genre) {
            Genre::firstOrCreate([
                'tmdb_id' => $genre['id'],
                'name' => $genre['name']
            ]);
        }
    }
}
