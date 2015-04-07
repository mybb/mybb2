<?php

namespace MyBB\Core\Renderers\Post\Quote;

use MyBB\Core\Database\Models\Post;
use MyBB\Core\Presenters\Post as PostPresenter;

class Markdown implements QuoteInterface
{
	/**
	 * @param Post $post
	 * @return string
	 */
	public function renderFromPost(Post $post)
	{
		$post = new PostPresenter($post);
		$message = $post->content;
		// TODO: MarkdownQuoteRenderer
		return "> {$message}";
	}
}