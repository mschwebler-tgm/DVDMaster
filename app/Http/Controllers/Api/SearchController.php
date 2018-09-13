<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Service\Facades\ContentTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private $isFirstQuery = true;

    public function search(Builder $builder, Request $request, $titleField)
    {
        $builder->where($titleField, 'like', "%{$request->get('title', '')}%");
        if ($request->has('genres')) {
            $this->addQuery($builder, 'whereHas', 'genres', function ($query) use ($request) {
                $query->whereIn('name', $request->get('genres', []));
            });
        }
        if ($request->has('actors')) {
            $this->addQuery($builder, 'whereHas', 'actors', function ($query) use ($request) {
                $query->whereIn('name', $request->get('actors', []));
            });
        }
        if ($boolFilters = $request->get('bool')) {
            $this->applyBoolfilters($builder, $boolFilters);
        }

        return $builder;
    }

    public function movies(Request $request)
    {
        $movies = Movie::with('actors', 'genres', 'pendingRental.user');
        $movies = $this->search($movies, $request, 'title');
        $movies = $movies->orderBy('title', 'asc')->paginate();
        return ContentTransformer::transformContentPaginaton($movies, 'movies');
    }

    private function applyBoolfilters(&$content, $boolFilters)
    {
        foreach ($boolFilters as $boolFilter) {
            if ($boolFilter === 'borrowed') {
                $this->addQuery($content, 'whereHas', 'pendingRental');
            } else {
                $this->addQuery($content, 'where', $boolFilter, 1);
            }
        }
    }

    private function addQuery(&$content, $method, $firstArg, $secondArg = null)
    {
        $methodPendants = [
            'where' => 'orWhere',
            'whereHas' => 'orWhereHas'
        ];

        if (!$this->isFirstQuery){
            $method = $methodPendants[$method];
        }
        $content->$method($firstArg, $secondArg);
        $this->isFirstQuery = false;
    }
}
