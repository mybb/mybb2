<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
| tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
| quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.
|
*/

Route::group(['prefix' => 'api/v1'], function ()
{
	Route::get('topics', ['as' => 'api.v1.topics.all', 'uses' => 'Api\TopicApiController@index']);
	Route::get('topic/{slug}', ['as' => 'api.v1.topics.show', 'uses' => 'Api\TopicApiController@show']);
});

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

Route::get('/', [
	'as' => 'forum.index',
	'middleware' => 'checkaccess',
	'except' => 'banned',
	'uses' => 'ForumController@index'
]);
Route::get('forums', ['as' => 'forums.all', 'uses' => 'ForumController@all']);
Route::get('forum/{slug}.{id}', ['as' => 'forums.show', 'uses' => 'ForumController@show']);

Route::get('topic/{slug}.{id}', ['as' => 'topics.show', 'uses' => 'TopicController@show']);
Route::get('topic/{slug}.{id}/post/{postId}', ['as' => 'topics.showPost', 'uses' => 'TopicController@showPost']);
Route::get('topic/{slug}.{id}/last', ['as' => 'topics.last', 'uses' => 'TopicController@last']);

Route::get('topic/{slug}.{id}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@reply']);
Route::get('topic/{slug}.{id}/reply/{postId}', ['as' => 'topics.quote', 'uses' => 'TopicController@reply']);

Route::post('topic/{slug}.{id}/reply', ['as' => 'topics.reply.post', 'uses' => 'TopicController@postReply']);

Route::get('topic/{slug}.{id}/edit/{postId}', ['as' => 'topics.edit', 'uses' => 'TopicController@edit']);
Route::post('topic/{slug}.{id}/edit/{postId}', ['as' => 'topics.edit', 'uses' => 'TopicController@postEdit']);

Route::get('topic/{slug}.{id}/delete/{postId}', ['as' => 'topics.delete', 'uses' => 'TopicController@delete']);
Route::get('topic/{slug}.{id}/restore/{postId}', ['as' => 'topics.restore', 'uses' => 'TopicController@restore']);

Route::get('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@create']);

Route::post('topic/create/{forumId}', ['as' => 'topics.create.post', 'uses' => 'TopicController@postCreate']);

Route::get('members', ['as' => 'members', 'uses' => 'MemberController@memberlist']);
Route::get('members/online', ['as' => 'members.online', 'uses' => 'MemberController@online']);

Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
Route::post('search', ['as' => 'search.post', 'uses' => 'SearchController@makeSearch']);
Route::get('search/{id}', ['as' => 'search.results', 'uses' => 'SearchController@results']);

Route::controllers([
	                   'auth' => 'Auth\AuthController',
	                   'password' => 'Auth\PasswordController',
                   ]);

Route::get('admin',
           ['middleware' => 'checkaccess', 'permissions' => 'admin_access', 'uses' => 'AdminController@index']);

Route::any('parser', ['uses' => 'DebugController@parser']);

Route::group(['prefix' => 'account', 'middleware' => 'checkaccess', 'permissions' => 'account_access'], function ()
{
	Route::get('/', ['as' => 'account.index', 'uses' => 'AccountController@index']);
	Route::get('/profile', ['as' => 'account.profile', 'uses' => 'AccountController@getProfile']);
	Route::post('/profile', ['as' => 'account.profile', 'uses' => 'AccountController@postProfile']);
	Route::get('/username', ['as' => 'account.username', 'uses' => 'AccountController@getUsername']);
	Route::post('/username', ['as' => 'account.username', 'uses' => 'AccountController@postUsername']);
	Route::get('/email', ['as' => 'account.email', 'uses' => 'AccountController@getEmail']);
	Route::post('/email', ['as' => 'account.email', 'uses' => 'AccountController@postEmail']);
	Route::get('/email/confirm/{token}', ['as' => 'account.email.confirm', 'uses' => 'AccountController@confirmEmail']);
	Route::get('/password', ['as' => 'account.password', 'uses' => 'AccountController@getPassword']);
	Route::post('/password', ['as' => 'account.password', 'uses' => 'AccountController@postPassword']);
	Route::get('/password/confirm/{token}',
	           ['as' => 'account.password.confirm', 'uses' => 'AccountController@confirmPassword']);
	Route::get('/avatar', ['as' => 'account.avatar', 'uses' => 'AccountController@getAvatar']);
	Route::post('/avatar', ['as' => 'account.avatar', 'uses' => 'AccountController@postAvatar']);
	Route::get('/avatar/remove', ['as' => 'account.avatar.remove', 'uses' => 'AccountController@removeAvatar']);
	Route::get('/notifications', ['as' => 'account.notifications', 'uses' => 'AccountController@getNotifications']);
	Route::get('/following', ['as' => 'account.following', 'uses' => 'AccountController@getFollowing']);
	Route::get('/buddies', ['as' => 'account.buddies', 'uses' => 'AccountController@getBuddies']);
	Route::get('/preferences', ['as' => 'account.preferences', 'uses' => 'AccountController@getPreferences']);
	Route::post('/preferences', ['as' => 'account.preferences', 'uses' => 'AccountController@postPreferences']);
	Route::get('/privacy', ['as' => 'account.privacy', 'uses' => 'AccountController@getPrivacy']);
	Route::post('/privacy', ['as' => 'account.privacy', 'uses' => 'AccountController@postPrivacy']);
	Route::get('/drafts', ['as' => 'account.drafts', 'uses' => 'AccountController@getDrafts']);
});
