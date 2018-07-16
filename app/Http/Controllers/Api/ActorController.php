<?php

namespace App\Http\Controllers;

use App\Actor;

class ActorController extends Controller
{
    public function actorNames()
    {
        return Actor::all()->pluck('name')->toArray();
    }
}