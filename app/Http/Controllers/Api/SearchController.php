<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function movies(Request $request)
    {
        return Movie::where('title', 'like', "%{$request->get('query')}%")
                      ->with('rentedBy', 'actors', 'genres', 'pendingRental')
                      ->orderBy('popularity', 'desc')->get();
    }
}