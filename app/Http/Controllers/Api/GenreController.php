<?php

namespace App\Http\Controllers;

use App\Genre;

class GenreController extends Controller
{
    public function genres()
    {
        return Genre::all();
    }
}