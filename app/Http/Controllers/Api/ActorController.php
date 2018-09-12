<?php

namespace App\Http\Controllers;

use App\Actor;
use Illuminate\Http\Request;
use TomLingham\Searchy\Facades\Searchy;

class ActorController extends Controller
{
    public function actors(Request $request)
    {
        $query = $request->get('q');
        if ($query) {
            return Searchy::driver('fuzzy')
                ->actors('name', 'also_known_as')->query($query)
                ->select('id', 'name', 'profile_path')
                ->getQuery()->limit(10)->get();
        }
        return Actor::select('id', 'name', 'profile_path')->orderBy('popularity', 'desc')->limit(10)->get();
    }
}
