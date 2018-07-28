<?php

namespace App\Console\Commands;

use App\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdateMovies extends Command
{
    protected $signature = 'update:movies';
    protected $description = 'Command description';

    public function handle()
    {
        $movies = Movie::whereNotNull('tmdb_id')->get();

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%]   %percent%% -- %message%');
        $bar = $this->output->createProgressBar(count($movies));
        $bar->setFormat('custom');

        foreach ($movies as $movie) {
            $bar->setMessage($movie->title);
            Artisan::call('import:movie', ['tmdbId' => $movie->tmdb_id]);
            $bar->advance();
        }
    }
}