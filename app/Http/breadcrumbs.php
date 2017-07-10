<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

Breadcrumbs::register('forum.index', function ($breadcrumbs) {

    $breadcrumbs->push(Settings::get('general.board_name', 'MyBB Community Forums'), route('forum.index'));
});

Breadcrumbs::register('forums.all', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('forum.allforums'), route('forums.all'));
});

Breadcrumbs::register('forums.show', function ($breadcrumbs, $forum) {

    if ($forum->parent_id) {
        $breadcrumbs->parent('forums.show', $forum->parent);
    } else {
        $breadcrumbs->parent('forum.index');
    }
    $breadcrumbs->push($forum->title, route('forums.show', ['slug' => $forum->slug, 'id' => $forum->id]));
});

Breadcrumbs::register('topics.show', function ($breadcrumbs, $topic) {

    if ($topic->forum) {
        $breadcrumbs->parent('forums.show', $topic->forum);
    }
    $breadcrumbs->push($topic->title, route('topics.show', ['sug' => $topic->slug, 'id' => $topic->id]));
});

Breadcrumbs::register('topics.reply', function ($breadcrumbs, $topic) {

    $breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(trans('topic.reply'), route('topics.reply', ['slug' => $topic->slug, 'id' => $topic->id]));
});

Breadcrumbs::register('topics.edit', function ($breadcrumbs, $topic, $post) {

    $breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(
        trans('topic.edit'),
        route('topics.edit', ['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id])
    );
});

Breadcrumbs::register('topics.create', function ($breadcrumbs, $forum) {

    $breadcrumbs->parent('forums.show', $forum);
    $breadcrumbs->push(
        trans('topic.create.title'),
        route('topics.create', ['slug' => $forum->slug, 'id' => $forum->id])
    );
});

Breadcrumbs::register('polls.create', function ($breadcrumbs, $topic) {

    $breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(trans('poll.addPoll'), route('polls.create', ['slug' => $topic->slug, 'id' => $topic->id]));
});

Breadcrumbs::register('polls.show', function ($breadcrumbs, $topic) {

    $breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(trans('poll.pollResults'), route('polls.show', ['slug' => $topic->slug, 'id' => $topic->id]));
});

Breadcrumbs::register('polls.edit', function ($breadcrumbs, $topic) {

    $breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(trans('poll.editPoll'), route('polls.edit', ['slug' => $topic->slug, 'id' => $topic->id]));
});

Breadcrumbs::register('members', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('member.members'), route('members'));
});

Breadcrumbs::register('members.online', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('member.online'), route('members.online'));
});

Breadcrumbs::register('account', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('account.youraccount'), route('account.index'));
});

Breadcrumbs::register('account.index', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
});

Breadcrumbs::register('account.profile', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.profile'), route('account.profile'));
});

Breadcrumbs::register('account.username', function ($breadcrumbs) {

    $breadcrumbs->parent('account.profile');
    $breadcrumbs->push(trans('account.username'), route('account.username'));
});

Breadcrumbs::register('account.email', function ($breadcrumbs) {

    $breadcrumbs->parent('account.profile');
    $breadcrumbs->push(trans('account.email'), route('account.email'));
});

Breadcrumbs::register('account.password', function ($breadcrumbs) {

    $breadcrumbs->parent('account.profile');
    $breadcrumbs->push(trans('account.password'), route('account.password'));
});

Breadcrumbs::register('account.password.confirm', function ($breadcrumbs) {

    $breadcrumbs->parent('account.password');
});

Breadcrumbs::register('account.avatar', function ($breadcrumbs) {

    $breadcrumbs->parent('account.profile');
    $breadcrumbs->push(trans('account.avatar'), route('account.avatar'));
});

Breadcrumbs::register('account.notifications', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.notifications'), route('account.notifications'));
});

Breadcrumbs::register('account.following', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.following'), route('account.following'));
});

Breadcrumbs::register('account.buddies', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.buddies'), route('account.buddies'));
});

Breadcrumbs::register('account.preferences', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.preferences'), route('account.preferences'));
});

Breadcrumbs::register('account.privacy', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.privacy'), route('account.privacy'));
});

Breadcrumbs::register('account.drafts', function ($breadcrumbs) {

    $breadcrumbs->parent('account');
    $breadcrumbs->push(trans('account.drafts'), route('account.drafts'));
});

Breadcrumbs::register('auth.signup', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('member.signup'), url('auth/signup'));
});

Breadcrumbs::register('auth.login', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('member.login'), url('auth/login'));
});

Breadcrumbs::register('search', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('search.search'), route('search'));
});

Breadcrumbs::register('search.results', function ($breadcrumbs, $searchlog) {

    $breadcrumbs->parent('search');
    $breadcrumbs->push(
        trans('search.resultsforx', ['keyword' => $searchlog->keywords]),
        route('search.results', ['id' => $searchlog->id])
    );
});

Breadcrumbs::register('conversations.index', function ($breadcrumbs) {

    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('conversations.conversations'), route('conversations.index'));
});

Breadcrumbs::register('conversations.compose', function ($breadcrumbs) {

    $breadcrumbs->parent('conversations.index');
    $breadcrumbs->push(trans('conversations.compose'), route('conversations.compose'));
});

Breadcrumbs::register('conversations.read', function ($breadcrumbs, $conversation) {

    $breadcrumbs->parent('conversations.index');
    $breadcrumbs->push($conversation->title, route('conversations.read', ['id' => $conversation->id]));
});

Breadcrumbs::register('conversations.leave', function ($breadcrumbs, $conversation) {

    $breadcrumbs->parent('conversations.read', $conversation);
    $breadcrumbs->push(trans('conversations.leave'), route('conversations.leave', ['id' => $conversation->id]));
});

Breadcrumbs::register('conversations.newParticipant', function ($breadcrumbs, $conversation) {

    $breadcrumbs->parent('conversations.read', $conversation);
    $breadcrumbs->push(
        trans('conversations.add_participants'),
        route('conversations.newParticipant', ['id' => $conversation->id])
    );
});

Breadcrumbs::register('user.profile', function ($breadcrumbs, $user) {
    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(
        trans('member.profileOf', ['name' => $user->name]),
        route('user.profile', ['slug' => $user->name, 'id' => $user->id])
    );
});

Breadcrumbs::register('admin', function ($breadcrumbs) {
    $breadcrumbs->push(trans('admin::general.control_panel'), route('admin.dashboard'));
});

Breadcrumbs::register('admin.dashboard', function ($breadcrumbs) {
    $breadcrumbs->parent('admin');
    $breadcrumbs->push(trans('admin::general.dashboard'), route('admin.dashboard'));
});

Breadcrumbs::register('admin.forums', function ($breadcrumbs) {
    $breadcrumbs->parent('admin');
    $breadcrumbs->push(trans('admin::general.forums_posts'), route('admin.forums'));
});

Breadcrumbs::register('admin.forums.management', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.forums');
    $breadcrumbs->push(trans('admin::forums.management'), route('admin.forums'));
});

Breadcrumbs::register('admin.forums.add', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.forums.management');
    $breadcrumbs->push(trans('admin::forums.add_forum'));
});

Breadcrumbs::register('admin.forums.delete', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.forums.management');
    $breadcrumbs->push(trans('admin::forums.delete_forum'));
});

Breadcrumbs::register('admin.forums.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.forums.management');
    $breadcrumbs->push(trans('admin::forums.edit_forum'));
});

Breadcrumbs::register('admin.users', function ($breadcrumbs) {
    $breadcrumbs->parent('admin');
    $breadcrumbs->push(trans('admin::general.users_roles'), route('admin.users'));
});

Breadcrumbs::register('admin.users.list', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users');
    $breadcrumbs->push(trans('admin::users.title'), route('admin.users'));
});

Breadcrumbs::register('admin.users.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.list');
    $breadcrumbs->push(trans('admin::users.edit_title'));
});

Breadcrumbs::register('admin.users.add', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.list');
    $breadcrumbs->push(trans('admin::users.add_title'));
});

Breadcrumbs::register('admin.users.delete', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.list');
    $breadcrumbs->push(trans('admin::users.delete_title'));
});

Breadcrumbs::register('admin.users.profile_fields', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users');
    $breadcrumbs->push(trans('admin::profile_fields.title'), route('admin.users.profile_fields'));
});

Breadcrumbs::register('admin.users.profile_fields.add', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.profile_fields');
    $breadcrumbs->push(trans('admin::profile_fields.add_field'));
});

Breadcrumbs::register('admin.users.profile_fields.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.profile_fields');
    $breadcrumbs->push(trans('admin::profile_fields.edit_field'));
});

Breadcrumbs::register('admin.users.profile_fields.edit_options', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.profile_fields');
    $breadcrumbs->push(trans('admin::profile_fields.edit_field_options'));
});

Breadcrumbs::register('admin.users.profile_fields.add_group', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users.profile_fields');
    $breadcrumbs->push(trans('admin::profile_fields.add_group'));
});

Breadcrumbs::register('admin.warnings.warning_types', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.users');
    $breadcrumbs->push(trans('admin::warnings.warning_types'));
});

Breadcrumbs::register('admin.warnings.add_warning_type', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.warnings.warning_types');
    $breadcrumbs->push(trans('admin::warnings.warning_types'));
});

Breadcrumbs::register('admin.warnings.warning_types.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.warnings.warning_types');
    $breadcrumbs->push(trans('admin::warnings.warning_types'));
});

Breadcrumbs::register('admin.tools.tasks', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('admin::tasks.scheduled_tasks'));
});

Breadcrumbs::register('admin.tools.tasks.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.tools.tasks');
    $breadcrumbs->push(trans('admin::tasks.add_task'));
});

Breadcrumbs::register('admin.tools.tasks.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.tools.tasks');
    $breadcrumbs->push(trans('admin::tasks.edit'));
});

Breadcrumbs::register('admin.tools.tasks.logs', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.tools.tasks');
    $breadcrumbs->push(trans('admin::tasks.view_logs'));
});

Breadcrumbs::register('moderation.control_panel', function ($breadcrumbs) {
    $breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('moderation.title'));
});

Breadcrumbs::register('moderation.warnings.warn_user', function ($breadcrumbs) {
    $breadcrumbs->parent('moderation.control_panel');
    $breadcrumbs->push(trans('warnings.warn_user'));
});
