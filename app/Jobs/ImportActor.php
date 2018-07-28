<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

class ImportActor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tmdbId;
    private $appendToMovie;

    public function __construct($tmdbId, $appendToMovie)
    {
        $this->tmdbId = $tmdbId;
        $this->appendToMovie = $appendToMovie;
    }

    public function handle()
    {
        Artisan::call('import:actor', ['tmdbId' => $this->tmdbId, 'appendToMovie' => $this->appendToMovie]);
    }
}
