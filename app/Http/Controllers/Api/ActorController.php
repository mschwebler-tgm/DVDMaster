<?php

namespace App\Http\Controllers;

use App\Actor;

class ActorController extends Controller
{
    public function actors()
    {
        return Actor::where('popularity', '>', 5)->get();
    }
}