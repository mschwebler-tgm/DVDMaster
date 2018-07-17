<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Service\Dao\MovieDao;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    private $movieDao;

    public function __construct(MovieDao $movieDao)
    {
        $this->movieDao = $movieDao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Movie::orderBy('popularity', 'desc')->get()->take(100);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $movie = $request->get('movie');
        if (!$movie) {
            abort(500);
        }

        $isCustom = (bool) $request->get('is_custom');
        if ($isCustom) {
            $posterPath = null;
            if ($request->file('custom_poster')) {
                $posterPath = $request->file('custom_poster')->store('posters', 'public');
            }
            $backdropPath = null;
            if ($request->file('custom_backdrop')) {
                $backdropPath = $request->file('custom_backdrop')->store('backdrops', 'public');
            }
            $this->movieDao->insertFromCustomArray($request->all(), $posterPath, $backdropPath);
        } else if ($isCustom === false) {
            $this->movieDao->insertFromArray($movie);
        }
    }

    public function show($id)
    {
        return Movie::find($id);
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        /** @var Movie $movie */
        $movie = Movie::find($id);
        if (!$movie) {
            abort(404);
        }
        $movie->delete();
        return response('success', 200);
    }
}
