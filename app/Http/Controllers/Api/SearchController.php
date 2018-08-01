<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function movies(Request $request)
    {
        $movies = Movie::with('rentedBy', 'actors', 'genres', 'pendingRental')
            ->orderBy('popularity', 'desc');

        $movies->where('title', 'like', "%{$request->get('query', '')}%");
        if ($request->has('genres')) {
            $movies->whereHas('genres', function ($query) use ($request) {
                $query->whereIn('name', $request->get('genres', []));
            });
        }
        if ($request->has('actors')) {
            $movies->whereHas('actors', function ($query) use ($request) {
                $query->whereIn('name', $request->get('actors', []));
            });
        }
        return $movies->get();
    }
}