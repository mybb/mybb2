<?php

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
	$breadcrumbs->push($forum->title, route('forums.show', [$forum->slug, $forum->id]));
});

Breadcrumbs::register('topics.show', function ($breadcrumbs, $topic) {

	if ($topic->forum) {
		$breadcrumbs->parent('forums.show', $topic->forum);
	}
	$breadcrumbs->push($topic->title, route('topics.show', [$topic->slug, $topic->id]));
});

Breadcrumbs::register('topics.reply', function ($breadcrumbs, $topic) {

	$breadcrumbs->parent('topics.show', $topic);
	$breadcrumbs->push(trans('topic.reply'), route('topics.reply', [$topic->slug, $topic->id]));
});

Breadcrumbs::register('topics.edit', function ($breadcrumbs, $topic) {

	$breadcrumbs->parent('topics.show', $topic);
	$breadcrumbs->push(trans('topic.edit'), route('topics.edit', [$topic->slug, $topic->id]));
});

Breadcrumbs::register('topics.create', function ($breadcrumbs, $forum) {

	$breadcrumbs->parent('forums.show', $forum);
	$breadcrumbs->push(trans('topic.create.title'), route('topics.create', [$forum->slug, $forum->id]));
});

Breadcrumbs::register('polls.create', function ($breadcrumbs, $topic) {

	$breadcrumbs->parent('topics.show', $topic);
	$breadcrumbs->push(trans('poll.addPoll'), route('polls.create', [$topic->slug, $topic->id]));
});

Breadcrumbs::register('polls.show', function ($breadcrumbs, $topic) {

	$breadcrumbs->parent('topics.show', $topic);
	$breadcrumbs->push(trans('poll.pollResults'), route('polls.show', [$topic->slug, $topic->id]));
});

Breadcrumbs::register('polls.edit', function ($breadcrumbs, $topic) {

	$breadcrumbs->parent('topics.show', $topic);
	$breadcrumbs->push(trans('poll.editPoll'), route('polls.edit', [$topic->slug, $topic->id]));
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

Breadcrumbs::register('user.profile', function ($breadcrumbs, $user) {
	$breadcrumbs->parent('forum.index');
	$breadcrumbs->push(
		trans('member.profileOf', ['name' => $user->name]),
		route('user.profile', ['slug' => $user->name, 'id' => $user->id])
	);
});
