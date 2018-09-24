<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Series;
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
        $this->search($movies, $request, 'title');
        $this->applyOrderFilters($movies, $request);
        $movies = $movies->orderBy('title', 'asc')->paginate();
        return ContentTransformer::transformContentPaginaton($movies, 'movies');
    }

    public function series(Request $request)
    {
        $series = Series::with('actors', 'genres', 'pendingRental.user');
        $this->search($series, $request, 'name');
        $this->applyOrderFilters($series, $request, ['title' => 'name', 'duration' => 'episode_runtime']);
        $series = $series->orderBy('name', 'asc')->paginate();
        return ContentTransformer::transformContentPaginaton($series, 'series');
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

    private function applyOrderFilters(Builder $builder, Request $request, $specialFields = [])
    {
        $orderFilter = json_decode($request->get('order'));
        if (!$orderFilter) {
            return;
        }
        $field = isset($specialFields[$orderFilter->field]) ? $specialFields[$orderFilter->field] : $orderFilter->field;
        $builder->orderBy($field, $orderFilter->direction);
    }
}
