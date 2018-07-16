<?php

namespace App\Http\Controllers;

use App\Genre;

class GenreController extends Controller
{
    public function genreNames()
    {
        return Genre::all()->pluck('name')->toArray();
    }
}