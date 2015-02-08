<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'forum.index', 'uses' => 'ForumController@index']);
Route::get('forum/{slug}', ['as' => 'forums.show', 'uses' => 'ForumController@show']);

Route::get('thread/{slug}', ['as' => 'threads.show', 'uses' => 'ThreadController@show']);

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
