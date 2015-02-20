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

Route::get('/', ['as' => 'forum.index', 'middleware' => 'checkaccess', 'except' => 'banned', 'uses' => 'ForumController@index']);
Route::get('forums', ['as' => 'forums.all', 'uses' => 'ForumController@all']);
Route::get('forum/{slug}', ['as' => 'forums.show', 'uses' => 'ForumController@show']);

Route::get('topic/{slug}', ['as' => 'topics.show', 'uses' => 'TopicController@show']);

Route::get('topic/{slug}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@reply']);

Route::post('topic/{slug}/reply', ['as' => 'topics.reply.post', 'uses' => 'TopicController@postReply']);

Route::get('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@create']);

Route::post('topic/create/{forumId}', ['as' => 'topics.create.post', 'uses' => 'TopicController@postCreate']);

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('admin', ['middleware' => 'checkaccess', 'permissions' => 'admin_access', 'uses' => 'AdminController@index']);

Route::any('parser', ['uses' => 'DebugController@parser']);
