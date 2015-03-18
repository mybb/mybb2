<?php

namespace MyBB\Core\Renderers\Post;

use MyBB\Core\Database\Models\Post;
use MyBB\Core\Presenters\Post as PostPresenter;

class Quote {
	/**
	 * @param Post $post
	 * @return string
	 */
	public function renderFromPost(Post $post)
	{
		$post = new PostPresenter($post);
		return "[quote='".e($post->author->name)."' pid='{$post->id}' dateline='".
				$post->created_at->getTimestamp()."']\n{$post->content}\n[/quote]";
	}
}