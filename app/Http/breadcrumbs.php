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
