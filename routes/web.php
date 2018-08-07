<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/jolly', 'JollyController@index');
Route::get('/jolly/images', 'JollyController@images');
Route::post('/jolly', 'JollyController@store');
Route::get('/{any}', 'HomeController@index')->where('any', '^(?!api\/|storage\/).+');