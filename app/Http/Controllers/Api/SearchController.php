<?php

namespace App\Http\Controllers;

use App\Movie;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private $isFirstQuery = true;

    public function movies(Request $request)
    {
        $movies = Movie::with('actors', 'genres', 'pendingRental.user');

        $movies->where('title', 'like', "%{$request->get('title', '')}%");
        if ($request->has('genres')) {
            $this->addQuery($movies, 'whereHas', 'genres', function ($query) use ($request) {
                $query->whereIn('name', $request->get('genres', []));
            });
        }
        if ($request->has('actors')) {
            $this->addQuery($movies, 'whereHas', 'actors', function ($query) use ($request) {
                $query->whereIn('name', $request->get('actors', []));
            });
        }
        if ($boolFilters = $request->get('bool')) {
            $this->applyBoolfilters($movies, $boolFilters);
        }
        return $movies->orderBy('title', 'asc')->paginate();
    }

    private function applyBoolfilters(&$movies, $boolFilters)
    {
        foreach ($boolFilters as $boolFilter) {
            if ($boolFilter === 'borrowed') {
                $this->addQuery($movies, 'whereHas', 'pendingRental');
            } else {
                $this->addQuery($movies, 'where', $boolFilter, 1);
            }
        }
    }

    private function addQuery(&$movies, $method, $firstArg, $secondArg = null)
    {
        $methodPendants = [
            'where' => 'orWhere',
            'whereHas' => 'orWhereHas'
        ];

        if (!$this->isFirstQuery){
            $method = $methodPendants[$method];
        }
        $movies->$method($firstArg, $secondArg);
        $this->isFirstQuery = false;
    }
}
