<?php

namespace App\Http\Controllers;

use App\Series;
use App\Service\Facades\ContentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Series::with('actors', 'genres', 'pendingRental.user')
            ->orderBy('name', 'asc')
            ->addSelect(DB::raw('series.*, "series" as data_type'))->paginate();
        return ContentTransformer::transformContentPaginaton($series, 'series');
    }

    public function store(Request $request)
    {
        $series = $request->get('series');
        if (!$series) {
            abort(500);
        }

        $isCustom = $request->get('is_custom') === 'true';
        if ($isCustom) {
            $posterPath = null;
            $posterPath = $this->savePoster($request);
            $backdropPath = null;
            $backdropPath = $this->saveBackdropImage($request);
            // TODO save custom series
//            $this->movieDao->insertFromCustomArray($request->all(), $posterPath, $backdropPath);
        } else if ($isCustom === false) {
            $reqSeries = json_decode($series, true);
            if (!Series::where('tmdb_id', $reqSeries['id'])->first()) {
                $series = new Series();
                $series->tmdb_id = $reqSeries['id'];
                $series->name = isset($reqSeries['name']) ? $reqSeries['name'] : '';
                $series->comment = isset($reqSeries['comment']) ? $reqSeries['comment'] : null;
                $series->blue_ray = isset($reqSeries['blue_ray']) ? $reqSeries['blue_ray'] : false;
                $series->based_on_book = isset($reqSeries['based_on_book']) ? $reqSeries['based_on_book'] : false;
                $series->true_story = isset($reqSeries['true_story']) ? $reqSeries['true_story'] : false;
                $series->save();
            }
            Artisan::call('import:series', ['tmdbId' => $reqSeries['id'], 'seasonsToExclude' => trim($request->get('removed_seasons'), '[]')]);
        }
    }

    public function show($id)
    {
    }

    public function destroy($id)
    {
    }

    public function updateLastSeen($id, $date)
    {
    }

    public function borrowTo($movieId, $userId)
    {
    }

    public function retrieveMovie($movieId, Request $request)
    {
    }

    public function rate($seriesId, Request $request)
    {
        $series = Series::find($seriesId);
        if (!$series || !$request->get('rating')) {
            abort(404);
        }

        $series->custom_rating = $request->get('rating');
        $series->save();
        return $series;
    }

    public function updateMovie($movieId, Request $request)
    {
    }

    private function savePoster(Request $request)
    {
    }

    private function saveBackdropImage(Request $request)
    {
    }
}
