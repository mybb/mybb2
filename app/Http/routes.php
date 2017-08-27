<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

/*
 * API Routes
 */

Route::group(['prefix' => 'api/v1'], function () {
    Route::get('topics', ['as' => 'api.v1.topics.all', 'uses' => 'Api\TopicApiController@index']);
    Route::get('topic/{slug}', ['as' => 'api.v1.topics.show', 'uses' => 'Api\TopicApiController@show']);
});
/*
 * Applicaiton Routes
 */

Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['as' => 'forum.index', 'uses' => 'ForumController@index']);

    Route::get('/schedule-manager', [
        'as' => 'forum.schedule-manager',
        'uses' => 'ScheduleManagerController@sendFakeImage',
        'middleware' => ['runtasks'],
    ]);

    Route::get('forums', ['as' => 'forums.all', 'uses' => 'ForumController@all']);
    Route::get('forum/{id}/{slug}', ['as' => 'forums.show', 'uses' => 'ForumController@show']);

    Route::get('topic/{id}/{slug}', ['as' => 'topics.show', 'uses' => 'TopicController@show']);
    Route::get('topic/{id}/{slug}/post/{postId}', ['as' => 'topics.showPost', 'uses' => 'TopicController@showPost']);
    Route::get('topic/{id}/{slug}/last', ['as' => 'topics.last', 'uses' => 'TopicController@last']);

    Route::get('topic/{id}/{slug}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@reply']);
    Route::get('topic/{id}/{slug}/reply/{postId}', ['as' => 'topics.quote', 'uses' => 'TopicController@reply']);

    Route::post('topic/{id}/{slug}/reply', ['as' => 'topics.reply', 'uses' => 'TopicController@postReply']);

    Route::get('topic/{id}/{slug}/edit/{postId}', ['as' => 'topics.edit', 'uses' => 'TopicController@edit']);
    Route::post('topic/{id}/{slug}/edit/{postId}', ['as' => 'topics.edit', 'uses' => 'TopicController@postEdit']);

    Route::get('topic/{id}/{slug}/delete/{postId}', ['as' => 'topics.delete', 'uses' => 'TopicController@delete']);
    Route::get('topic/{id}/{slug}/restore/{postId}', ['as' => 'topics.restore', 'uses' => 'TopicController@restore']);

    Route::get('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@create']);
    Route::post('topic/create/{forumId}', ['as' => 'topics.create', 'uses' => 'TopicController@postCreate']);

    Route::get('topic/{id}/{slug}/poll/create', ['as' => 'polls.create', 'uses' => 'PollController@create']);
    Route::post('topic/{id}/{slug}/poll/create', ['as' => 'polls.postCreate', 'uses' => 'PollController@postCreate']);
    Route::post('topic/{topicId}/{topicSlug}/poll/vote', ['as' => 'polls.vote', 'uses' => 'PollController@vote']);
    Route::get('topic/{topicId}/{topicSlug}/poll/', ['as' => 'polls.show', 'uses' => 'PollController@show']);
    Route::get('topic/{topicId}/{topicSlug}/poll/undo', ['as' => 'polls.undo', 'uses' => 'PollController@undo']);
    Route::get('topic/{topicId}/{topicSlug}/poll/remove', ['as' => 'polls.remove', 'uses' => 'PollController@remove']);
    Route::get('topic/{topicId}/{topicSlug}/poll/edit', ['as' => 'polls.edit', 'uses' => 'PollController@edit']);
    Route::post(
        'topic/{topicId}/{topicSlug}/poll/edit',
        ['as' => 'polls.edit.post', 'uses' => 'PollController@postEdit']
    );

    Route::get('post/{id}', ['as' => 'posts.show', 'uses' => 'PostController@show']);
    Route::post('post/{post_id}/like', ['as' => 'posts.like', 'uses' => 'PostController@postToggleLike']);
    Route::get('post/{post_id}/likes', ['as' => 'post.likes', 'uses' => 'PostController@getPostLikes']);

    Route::post('post/quotes/all', ['as' => 'post.viewQuotes', 'uses' => 'PostController@viewQuotes']);
    Route::post('post/quotes', ['as' => 'post.quotes', 'uses' => 'PostController@postQuotes']);

    Route::get('members', ['as' => 'members', 'uses' => 'MemberController@memberlist']);
    Route::get('members/online', ['as' => 'members.online', 'uses' => 'MemberController@online']);

    Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
    Route::post('search', ['as' => 'search.post', 'uses' => 'SearchController@makeSearch']);
    Route::get('search/{token}', ['as' => 'search.results', 'uses' => 'SearchController@results']);

    Auth::routes();

    Route::get('/user/{id}/{slug}', ['as' => 'user.profile', 'uses' => 'UserController@profile']);
    Route::get(
        '/user/{id}/{slug}/warns',
        ['as' => 'user.profile.warns', 'uses' => 'WarningsController@showWarnsForUser']
    );
    Route::get('/warnings/show/{warnId}', ['as' => 'warnings.show', 'uses' => 'WarningsController@warnDetails']);
    Route::get('/warnings/ack', ['as' => 'warnings.ack', 'uses' => 'WarningsController@acknowledgeWithWarn']);
    Route::post('/warnings/ack', ['as' => 'warnings.ack', 'uses' => 'WarningsController@postAcknowledgeWithWarn']);

    Route::group(['prefix' => 'admin', 'middleware' => 'checkaccess', 'permissions' => 'canEnterACP'], function () {
        Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@index']);

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [
                'as'   => 'admin.users',
                'uses' => 'Admin\Users\UserController@users',
            ]);
            Route::get('/edit/{id}', [
                'as'   => 'admin.users.edit',
                'uses' => 'Admin\Users\UserController@editUser',
            ]);
            Route::post('/edit/{id}', [
                'as'   => 'admin.users.edit',
                'uses' => 'Admin\Users\UserController@saveUser',
            ]);
            Route::get('/delete/{id}', [
                'as'   => 'admin.users.delete',
                'uses' => 'Admin\Users\UserController@delete',
            ]);
            Route::post('/delete', [
                'as'   => 'admin.users.delete',
                'uses' => 'Admin\Users\UserController@deleteUser',
            ]);
            Route::get('/add', [
                'as'   => 'admin.users.add',
                'uses' => 'Admin\Users\UserController@addUser',
            ]);
            Route::post('/add', [
                'as'   => 'admin.users.add',
                'uses' => 'Admin\Users\UserController@create',
            ]);
            Route::get('/profile-fields', [
                'as'   => 'admin.users.profile_fields',
                'uses' => 'Admin\Users\ProfileFieldController@profileFields',
            ]);
            Route::get('/profile-fields/add', [
                'as'   => 'admin.users.profile_fields.add',
                'uses' => 'Admin\Users\ProfileFieldController@addProfileField',
            ]);
            Route::post('/profile-fields/add', [
                'as'   => 'admin.users.profile_fields.add',
                'uses' => 'Admin\Users\ProfileFieldController@saveNewProfileField',
            ]);
            Route::post('/profile-fields/delete', [
                'as'   => 'admin.users.profile_fields.delete',
                'uses' => 'Admin\Users\ProfileFieldController@deleteProfileField',
            ]);
            Route::get('/profile-fields/edit/{id}', [
                'as'   => 'admin.users.profile_fields.edit',
                'uses' => 'Admin\Users\ProfileFieldController@editProfileField',
            ]);
            Route::post('/profile-fields/edit/{id}', [
                'as'   => 'admin.users.profile_fields.edit',
                'uses' => 'Admin\Users\ProfileFieldController@saveProfileField',
            ]);
            Route::get('/profile-fields/edit-options/{id}', [
                'as'   => 'admin.users.profile_fields.edit_options',
                'uses' => 'Admin\Users\ProfileFieldController@editProfileFieldOptions',
            ]);
            Route::post('/profile-fields/delete-option', [
                'as'   => 'admin.users.profile_fields.delete_option',
                'uses' => 'Admin\Users\ProfileFieldController@deleteProfileFieldOption',
            ]);
            Route::post('/profile-fields/add-option', [
                'as'   => 'admin.users.profile_fields.add_option',
                'uses' => 'Admin\Users\ProfileFieldController@saveNewProfileFieldOption',
            ]);
            Route::get('/profile-fields/add-group', [
                'as'   => 'admin.users.profile_fields.add_group',
                'uses' => 'Admin\Users\ProfileFieldController@addProfileFieldGroup',
            ]);
            Route::post('/profile-fields/add-group', [
                'as'   => 'admin.users.profile_fields.add_group',
                'uses' => 'Admin\Users\ProfileFieldController@saveNewProfileFieldGroup',
            ]);
            Route::post('/profile-fields/test', [
                'as'   => 'admin.users.profile_fields.test',
                'uses' => 'Admin\Users\ProfileFieldController@testSubmit',
            ]);
        });
        Route::group(['prefix' => 'warnings'], function () {
            Route::get('warning-types', [
                'as'   => 'admin.warnings.warning_types',
                'uses' => 'Admin\Users\WarningsController@warningTypes',
            ]);
            Route::get('warning-types/add', [
                'as'   => 'admin.warnings.add_warning_type',
                'uses' => 'Admin\Users\WarningsController@addWarningType',
            ]);
            Route::post('warning-types/add', [
                'as'   => 'admin.warnings.add_warning_type',
                'uses' => 'Admin\Users\WarningsController@createWarningType',
            ]);
            Route::get('warning-types/edit/{id}', [
                'as'   => 'admin.warnings.warning_types.edit',
                'uses' => 'Admin\Users\WarningsController@editWarningType',
            ]);
            Route::post('warning-types/edit/{id}', [
                'as'   => 'admin.warnings.warning_types.edit',
                'uses' => 'Admin\Users\WarningsController@saveWarningType',
            ]);
            Route::post('warning-types/delete', [
                'as'   => 'admin.warnings.warning_types.delete',
                'uses' => 'Admin\Users\WarningsController@deleteWarningType',
            ]);
        });
        Route::group(['prefix' => 'forums'], function () {
            Route::get('/', [
                'as'   => 'admin.forums',
                'uses' => 'Admin\Forums\ForumsController@show',
            ]);
            Route::get('/add', [
                'as'   => 'admin.forums.add',
                'uses' => 'Admin\Forums\ForumsController@add',
            ]);
            Route::post('/add', [
                'as'   => 'admin.forums.add',
                'uses' => 'Admin\Forums\ForumsController@create',
            ]);
            Route::post('/delete', [
                'as'   => 'admin.forums.delete',
                'uses' => 'Admin\Forums\ForumsController@delete',
            ]);
            Route::get('/edit/{id}', [
                'as'   => 'admin.forums.edit',
                'uses' => 'Admin\Forums\ForumsController@edit',
            ]);
            Route::post('/edit/{id}', [
                'as'   => 'admin.forums.edit',
                'uses' => 'Admin\Forums\ForumsController@update',
            ]);
        });
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', [
                'as'   => 'admin.settings',
                'uses' => 'Admin\Settings\SettingsController@listGroups',
            ]);
            Route::get('edit/{group}/{package?}', [
                'as'   => 'admin.settings.edit',
                'uses' => 'Admin\Settings\SettingsController@editGroupOfSettings',
            ]);
            Route::post('edit/{group}/{package?}', [
                'as'   => 'admin.settings.edit',
                'uses' => 'Admin\Settings\SettingsController@saveGroupOfSettings',
            ]);
        });
        Route::group(['prefix' => 'tools'], function () {
            Route::get('/tasks', [
                'as'   => 'admin.tools.tasks',
                'uses' => 'Admin\Tools\TasksController@show',
            ]);
            Route::get('/tasks/run/{id}', [
                'as'   => 'admin.tools.tasks.run',
                'uses' => 'Admin\Tools\TasksController@run',
            ]);
            Route::get('/tasks/edit/{id}', [
                'as'   => 'admin.tools.tasks.edit',
                'uses' => 'Admin\Tools\TasksController@edit',
            ]);
            Route::post('/tasks/edit/{id}', [
                'as'   => 'admin.tools.tasks.edit',
                'uses' => 'Admin\Tools\TasksController@update',
            ]);
            Route::get('/tasks/create', [
                'as'   => 'admin.tools.tasks.create',
                'uses' => 'Admin\Tools\TasksController@create',
            ]);
            Route::post('/tasks/create', [
                'as'   => 'admin.tools.tasks.create',
                'uses' => 'Admin\Tools\TasksController@save',
            ]);
            Route::post('/tasks/disable-enable', [
                'as'   => 'admin.tools.tasks.disable_enable',
                'uses' => 'Admin\Tools\TasksController@disableEnable',
            ]);
            Route::post('/tasks/delete', [
                'as'   => 'admin.tools.tasks.delete',
                'uses' => 'Admin\Tools\TasksController@delete',
            ]);
            Route::get('/tasks/logs/{id?}', [
                'as'   => 'admin.tools.tasks.logs',
                'uses' => 'Admin\Tools\TasksController@logs',
            ]);
        });
    });

    Route::get('captcha/{imagehash}', ['as' => 'captcha', 'uses' => 'CaptchaController@captcha', 'noOnline' => true]);

    Route::post('/moderate', ['as' => 'moderate', 'uses' => 'ModerationController@moderate']);
    Route::post('/moderate/reverse', ['as' => 'moderate.reverse', 'uses' => 'ModerationController@reverse']);
    Route::get('/moderate/form/{moderationName}', ['as' => 'moderate.form', 'uses' => 'ModerationController@form']);

    Route::group(['prefix' => 'moderation'], function () {
        Route::group([
            'prefix' => 'control-panel',
            ['middleware' => 'checkaccess', 'permissions' => 'canEnterMCP'],
        ], function () {
            Route::get('/', ['as' => 'moderation.control_panel', 'uses' => 'ModerationController@controlPanel']);
            Route::get('/queue', [
                'as'   => 'moderation.control_panel.queue',
                'uses' => 'ModerationController@queue',
            ]);
            Route::get('/logs', [
                'as'   => 'moderation.control_panel.logs',
                'uses' => 'ModerationController@logs',
            ]);
        });
        Route::get('/warn/{userId}/{contentType}/{contentId}', [
            'as'   => 'moderation.warnings.warn_user',
            'uses' => 'WarningsController@warnUser',
        ]);
        Route::post('/warn/{userId}/{contentType}/{contentId}', [
            'as'   => 'moderation.warnings.warn_user',
            'uses' => 'WarningsController@createWarnUser',
        ]);
        Route::post('/warn/revoke', [
            'as'   => 'moderation.warnings.warn_revoke',
            'uses' => 'WarningsController@revokeWarn',
        ]);
    });

    Route::group(['prefix' => 'account', 'middleware' => 'checkaccess', 'permissions' => 'canEnterUCP'], function () {
        Route::get('/', ['as' => 'account.index', 'uses' => 'AccountController@index']);
        Route::get('/profile', ['as' => 'account.profile', 'uses' => 'AccountController@getProfile']);
        Route::post('/profile', ['as' => 'account.profile', 'uses' => 'AccountController@postProfile']);
        Route::get('/username', ['as' => 'account.username', 'uses' => 'AccountController@getUsername']);
        Route::post('/username', ['as' => 'account.username', 'uses' => 'AccountController@postUsername']);
        Route::get('/email', ['as' => 'account.email', 'uses' => 'AccountController@getEmail']);
        Route::post('/email', ['as' => 'account.email', 'uses' => 'AccountController@postEmail']);
        Route::get(
            '/email/confirm/{token}',
            ['as' => 'account.email.confirm', 'uses' => 'AccountController@confirmEmail']
        );
        Route::get('/password', ['as' => 'account.password', 'uses' => 'AccountController@getPassword']);
        Route::post('/password', ['as' => 'account.password', 'uses' => 'AccountController@postPassword']);
        Route::get(
            '/password/confirm/{token}',
            ['as' => 'account.password.confirm', 'uses' => 'AccountController@confirmPassword']
        );
        Route::get('/avatar', ['as' => 'account.avatar', 'uses' => 'AccountController@getAvatar']);
        Route::post('/avatar', ['as' => 'account.avatar', 'uses' => 'AccountController@postAvatar']);
        Route::post('/avatar/crop', ['as' => 'account.avatar.crop', 'uses' => 'AccountController@postAvatarCrop']);
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
        'prefix'      => 'conversations',
        'middleware'  => ['checkaccess', 'checksetting'],
        'permissions' => 'canUseConversations',
        'setting'     => 'conversations.enabled',
    ], function () {
        Route::get('/', ['as' => 'conversations.index', 'uses' => 'ConversationsController@index']);
        Route::get('/compose', ['as' => 'conversations.compose', 'uses' => 'ConversationsController@getCompose']);
        Route::post('/compose', ['as' => 'conversations.compose', 'uses' => 'ConversationsController@postCompose']);
        Route::get('/read/{id}', ['as' => 'conversations.read', 'uses' => 'ConversationsController@getRead']);
        Route::post('/read/{id}/reply', ['as' => 'conversations.reply', 'uses' => 'ConversationsController@postReply']);
        Route::get('read/{id}/leave', ['as' => 'conversations.leave', 'uses' => 'ConversationsController@getLeave']);
        Route::post('read/{id}/leave', ['as' => 'conversations.leave', 'uses' => 'ConversationsController@postLeave']);
        Route::get(
            '/read/{id}/newParticipant',
            ['as' => 'conversations.newParticipant', 'uses' => 'ConversationsController@getNewParticipant']
        );
        Route::post(
            '/read/{id}/newParticipant',
            ['as' => 'conversations.newParticipant', 'uses' => 'ConversationsController@postNewParticipant']
        );
    });
});
