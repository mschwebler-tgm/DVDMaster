<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['adminOnly']], function () {
    // movies
    Route::get('/movie/{id}/delete', 'MovieController@destroy');
    Route::get('/movie/{id}/lastSeen/{date}', 'MovieController@updateLastSeen');
    Route::post('/movie/{movieId}/borrowTo/{userId}', 'MovieController@borrowTo');
    Route::post('/movie/{movieId}/retrieve', 'MovieController@retrieveMovie');
    Route::post('/movie/{movieId}/rate', 'MovieController@rateMovie');
    Route::post('/movie/{movieId}/update', 'MovieController@updateMovie');
    Route::post('/movie', 'MovieController@store');

    // series
    Route::post('/series', 'SeriesController@store');
    Route::post('/series/{seriesId}/rate', 'SeriesController@rate');

    // other
    Route::post('/users', 'UserController@create');
});

// movies
Route::get('/movies', 'MovieController@index');
Route::get('/movie/{id}', 'MovieController@show');

//series
Route::get('/series', 'SeriesController@index');

Route::get('/actors', 'ActorController@actors');
Route::get('/genres', 'GenreController@genres');
Route::get('/users/{id}', 'UserController@getAllExcept');
Route::get('/stats', 'StatsController@index');

Route::get('/customSearch/movies', 'SearchController@movies');
Route::get('/customSearch/series', 'SearchController@series');
