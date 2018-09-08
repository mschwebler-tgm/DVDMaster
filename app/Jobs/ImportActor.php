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
    private $appendTo;
    private $appendToId;

    public function __construct($tmdbId, $appendTo, $id)
    {
        $this->tmdbId = $tmdbId;
        $this->appendTo = $appendTo;
        $this->appendToId = $id;
    }

    public function handle()
    {
        Artisan::call('import:actor', ['tmdbId' => $this->tmdbId, "appendTo{$this->appendTo}" => $this->appendToId]);
    }
}
