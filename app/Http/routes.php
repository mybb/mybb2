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

Route::group(['prefix' => 'api/v1'], function () {
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

Route::get('/', ['as' => 'forum.index', 'uses' => 'ForumController@index']);

Route::get('forums', ['as' => 'forums.all', 'uses' => 'ForumController@all']);
Route::get('forum/{slug}.{id}', ['as' => 'forums.show', 'uses' => 'ForumController@show']);

Route::get('topic/{slug}.{id}', ['as' => 'topics.show', 'uses' => 'TopicController@show']);
Route::get('topic/{slug}.{id}/post/{postId}', ['as' => 'topics.showPost', 'uses' => 'TopicController@showPost']);
Route::get('topic/{slug}.{id}/last', ['as' => 'topics.last', 'uses' => 'TopicController@last']);

Route::get('topic/{slug}.{id}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@reply']);
Route::get('topic/{slug}.{id}/reply/{postId}', ['as' => 'topics.quote', 'uses' => 'TopicController@reply']);

Route::post('topic/{slug}.{id}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@postReply']);

Route::get('topic/{slug}.{id}/edit/{postId}', ['as' => 'topics.edit', 'uses' => 'TopicController@edit']);
Route::post('topic/{slug}.{id}/edit/{postId}', ['as' => 'topics.edit', 'uses' => 'TopicController@postEdit']);

Route::get('topic/{slug}.{id}/delete/{postId}', ['as' => 'topics.delete', 'uses' => 'TopicController@delete']);
Route::get('topic/{slug}.{id}/restore/{postId}', ['as' => 'topics.restore', 'uses' => 'TopicController@restore']);

Route::get('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@create']);
Route::post('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@postCreate']);

Route::get('topic/{slug}.{id}/poll/create', ['as' => 'polls.create', 'uses' => 'PollController@create']);
Route::post('topic/{slug}.{id}/poll/create', ['as' => 'polls.postCreate', 'uses' => 'PollController@postCreate']);
Route::post('topic/{topicSlug}.{topicId}/poll/vote', ['as' => 'polls.vote', 'uses' => 'PollController@vote']);
Route::get('topic/{topicSlug}.{topicId}/poll/', ['as' => 'polls.show', 'uses' => 'PollController@show']);
Route::get('topic/{topicSlug}.{topicId}/poll/undo', ['as' => 'polls.undo', 'uses' => 'PollController@undo']);
Route::get('topic/{topicSlug}.{topicId}/poll/remove', ['as' => 'polls.remove', 'uses' => 'PollController@remove']);
Route::get('topic/{topicSlug}.{topicId}/poll/edit', ['as' => 'polls.edit', 'uses' => 'PollController@edit']);
Route::post('topic/{topicSlug}.{topicId}/poll/edit', ['as' => 'polls.edit.post', 'uses' => 'PollController@postEdit']);

Route::post('post/{post_id}/like', ['as' => 'posts.like', 'uses' => 'PostController@postToggleLike']);
Route::get('post/{post_id}/likes', ['as' => 'post.likes', 'uses' => 'PostController@getPostLikes']);

Route::get('members', ['as' => 'members', 'uses' => 'MemberController@memberlist']);
Route::get('members/online', ['as' => 'members.online', 'uses' => 'MemberController@online']);

Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
Route::post('search', ['as' => 'search.post', 'uses' => 'SearchController@makeSearch']);
Route::get('search/{id}', ['as' => 'search.results', 'uses' => 'SearchController@results']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/user/{slug}.{id}', ['as' => 'user.profile', 'uses' => 'UserController@profile']);

Route::get('admin',
	['middleware' => 'checkaccess', 'permissions' => 'admin_access', 'uses' => 'AdminController@index']);

Route::get('captcha/{imagehash}', ['as' => 'captcha', 'uses' => 'CaptchaController@captcha', 'noOnline' => true]);

Route::any('parser', ['uses' => 'DebugController@parser']);

Route::group(['prefix' => 'account', 'middleware' => 'checkaccess', 'permissions' => 'canEnterUCP'], function () {
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

Route::group([
	'prefix' => 'conversations',
	'middleware' => ['checkaccess','checksetting'],
	'permissions' => 'canUseConversations',
	'setting' => 'conversations.enabled'
], function () {
	Route::get('/', ['as' => 'conversations.index', 'uses' => 'ConversationsController@index']);
	Route::get('/compose', ['as' => 'conversations.compose', 'uses' => 'ConversationsController@getCompose']);
	Route::post('/compose', ['as' => 'conversations.compose', 'uses' => 'ConversationsController@postCompose']);
	Route::get('/read/{id}', ['as' => 'conversations.read', 'uses' => 'ConversationsController@getRead']);
	Route::post('/read/{id}/reply', ['as' => 'conversations.reply', 'uses' => 'ConversationsController@postReply']);
	Route::post('/read/{id}/newRecipient', ['as' => 'conversations.newRecipient', 'uses' => 'ConversationsController@postNewRecipient']);
});
