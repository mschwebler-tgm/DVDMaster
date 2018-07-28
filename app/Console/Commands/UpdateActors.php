<?php

namespace App\Console\Commands;

use App\Actor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Helper\ProgressBar;

class UpdateActors extends Command
{
    protected $signature = 'update:actors';
    protected $description = 'Command description';

    public function handle()
    {
        $actors = Actor::whereNotNull('tmdb_id')->get();

        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%]   %percent%% -- %message%');
        $bar = $this->output->createProgressBar(count($actors));
        $bar->setFormat('custom');

        foreach ($actors as $actor) {
            $bar->setMessage($actor->name);
            Artisan::call('import:actor', ['tmdbId' => $actor->tmdb_id]);
            $bar->advance();
        }
    }
}