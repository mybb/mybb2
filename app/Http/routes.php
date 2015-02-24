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
Route::get('topic/{slug}/last', ['as' => 'topics.last', 'uses' => 'TopicController@last']);

Route::get('topic/{slug}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@reply']);

Route::post('topic/{slug}/reply', ['as' => 'topics.reply.post', 'uses' => 'TopicController@postReply']);

Route::get('topic/{slug}/edit/{id}', ['as' => 'topics.edit', 'uses' => 'TopicController@edit']);
Route::post('topic/{slug}/edit/{id}', ['as' => 'topics.edit', 'uses' => 'TopicController@postEdit']);

Route::get('topic/{slug}/delete/{id}', ['as' => 'topics.delete', 'uses' => 'TopicController@delete']);

Route::get('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@create']);

Route::post('topic/create/{forumId}', ['as' => 'topics.create.post', 'uses' => 'TopicController@postCreate']);

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('admin', ['middleware' => 'checkaccess', 'permissions' => 'admin_access', 'uses' => 'AdminController@index']);

Route::any('parser', ['uses' => 'DebugController@parser']);

Route::group(['prefix' => 'account', 'middleware' => 'checkaccess', 'permissions' => 'account_access'], function()
{
    Route::get('/', ['as' => 'account.index', 'uses' => 'AccountController@index']);
    Route::get('/profile', ['as' => 'account.profile', 'uses' => 'AccountController@getProfile']);
    Route::get('/notifications', ['as' => 'account.notifications', 'uses' => 'AccountController@getNotifications']);
    Route::get('/following', ['as' => 'account.following', 'uses' => 'AccountController@getFollowing']);
    Route::get('/buddies', ['as' => 'account.buddies', 'uses' => 'AccountController@getBuddies']);
    Route::get('/preferences', ['as' => 'account.preferences', 'uses' => 'AccountController@getPreferences']);
    Route::post('/preferences', ['as' => 'account.preferences', 'uses' => 'AccountController@postPreferences']);
    Route::get('/privacy', ['as' => 'account.privacy', 'uses' => 'AccountController@getPrivacy']);
    Route::get('/drafts', ['as' => 'account.drafts', 'uses' => 'AccountController@getDrafts']);
});