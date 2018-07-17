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

Route::get('/movies', 'MovieController@index');
Route::get('/movie/{id}', 'MovieController@show');
Route::get('/movie/{id}/delete', 'MovieController@destroy');
Route::get('/genreNames', 'GenreController@genreNames');
Route::get('/actorNames', 'ActorController@actorNames');
Route::get('/movie/{id}', 'MovieController@show');
Route::post('/movie', 'MovieController@store');
