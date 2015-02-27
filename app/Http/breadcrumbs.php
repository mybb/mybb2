<?php

Breadcrumbs::register('forum.index', function($breadcrumbs)
{
    $breadcrumbs->push('MyBB Community', route('forum.index'));
});

Breadcrumbs::register('forums.all', function($breadcrumbs)
{
	$breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('forum.allforums'), route('forums.all'));
});

Breadcrumbs::register('forums.show', function($breadcrumbs, $forum)
{
	if($forum->parent_id)
	{
		$breadcrumbs->parent('forums.show', $forum->parent);
	}
	else
	{
		$breadcrumbs->parent('forum.index');
	}
    $breadcrumbs->push($forum->title, route('forums.show', [$forum->slug]));
});

Breadcrumbs::register('topics.show', function($breadcrumbs, $topic)
{
	if($topic->forum)
	{
		$breadcrumbs->parent('forums.show', $topic->forum);
	}
    $breadcrumbs->push($topic->title, route('topics.show', [$topic->slug]));
});

Breadcrumbs::register('topics.reply', function($breadcrumbs, $topic)
{
	$breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(trans('topic.reply'), route('topics.reply', [$topic->slug]));
});

Breadcrumbs::register('topics.edit', function($breadcrumbs, $topic)
{
	$breadcrumbs->parent('topics.show', $topic);
    $breadcrumbs->push(trans('topic.edit'), route('topics.edit', [$topic->slug]));
});

Breadcrumbs::register('topics.create', function($breadcrumbs, $forum)
{
	$breadcrumbs->parent('forums.show', $forum);
    $breadcrumbs->push(trans('topic.create.title'), route('topics.create', [$forum->slug]));
});

Breadcrumbs::register('members', function($breadcrumbs)
{
	$breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('member.members'), route('members'));
});

Breadcrumbs::register('account', function($breadcrumbs)
{
	$breadcrumbs->parent('forum.index');
    $breadcrumbs->push(trans('account.youraccount'), route('account.index'));
});

Breadcrumbs::register('account.index', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
});

Breadcrumbs::register('account.profile', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.profile'), route('account.profile'));
});

Breadcrumbs::register('account.username', function($breadcrumbs)
{
	$breadcrumbs->parent('account.profile');
	$breadcrumbs->push(trans('account.username'), route('account.username'));
});

Breadcrumbs::register('account.email', function($breadcrumbs)
{
	$breadcrumbs->parent('account.profile');
	$breadcrumbs->push(trans('account.email'), route('account.email'));
});

Breadcrumbs::register('account.password', function($breadcrumbs)
{
	$breadcrumbs->parent('account.profile');
	$breadcrumbs->push(trans('account.password'), route('account.password'));
});

Breadcrumbs::register('account.password.confirm', function($breadcrumbs)
{
	$breadcrumbs->parent('account.password');
});

Breadcrumbs::register('account.avatar', function($breadcrumbs)
{
	$breadcrumbs->parent('account.profile');
	$breadcrumbs->push(trans('account.avatar'), route('account.avatar'));
});

Breadcrumbs::register('account.notifications', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.notifications'), route('account.notifications'));
});

Breadcrumbs::register('account.following', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.following'), route('account.following'));
});

Breadcrumbs::register('account.buddies', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.buddies'), route('account.buddies'));
});

Breadcrumbs::register('account.preferences', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.preferences'), route('account.preferences'));
});

Breadcrumbs::register('account.privacy', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.privacy'), route('account.privacy'));
});

Breadcrumbs::register('account.drafts', function($breadcrumbs)
{
	$breadcrumbs->parent('account');
	$breadcrumbs->push(trans('account.drafts'), route('account.drafts'));
});