<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// for a named route to a controller action. hii hukujua bana
Route::get('/', [ 'uses'=> 'SnippetsController@getIndex']);

Route::controller('users', 'UsersController');
Route::controller('snippets', 'SnippetsController');
Route::controller('favorites', 'FavoritesController');

// Event::listen('illuminate.query', function($sql, $bindings, $times){

// 	var_dump($sql, $bindings, $times);
// });