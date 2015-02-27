<?php

Breadcrumbs::register('forum.index', function($breadcrumbs)
{
    $breadcrumbs->push('MyBB Community', route('forum.index'));
});

Breadcrumbs::register('forum', function($breadcrumbs, $forum)
{
	if($forum->parent_id)
	{
		$breadcrumbs->parent('forum', $forum->parent);
	}
    $breadcrumbs->push($forum->title, route('forums.show', [$forum->slug]));
});

Breadcrumbs::register('topic', function($breadcrumbs, $topic)
{
	if($topic->forum)
	{
		$breadcrumbs->parent('forum', $topic->forum);
	}
    $breadcrumbs->push($topic->title, route('topics.show', [$topic->slug]));
});
