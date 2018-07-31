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
Route::post('/movie/{movieId}/borrowTo/{userId}', 'MovieController@borrowTo');
Route::post('/movie/{movieId}/retrieve', 'MovieController@retrieveMovie');
Route::post('/movie/{movieId}/rate', 'MovieController@rateMovie');
Route::get('/movie/{id}/lastSeen/{date}', 'MovieController@updateLastSeen');
Route::get('/genres', 'GenreController@genres');
Route::get('/actors', 'ActorController@actors');
Route::get('/movie/{id}', 'MovieController@show');
Route::post('/movie', 'MovieController@store');
Route::get('/users/{id}', 'UserController@getAllExcept');
