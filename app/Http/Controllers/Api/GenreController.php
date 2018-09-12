<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;
use TomLingham\Searchy\Facades\Searchy;

class GenreController extends Controller
{
    public function genres(Request $request)
    {
        $query = $request->get('q');
        if ($query) {
            return Searchy::driver('fuzzy')
                ->genres('name')->query($query)
                ->select('id', 'name')
                ->getQuery()->limit(30)->get();
        }
        return Genre::select('id', 'name')->limit(30)->get();
    }
}