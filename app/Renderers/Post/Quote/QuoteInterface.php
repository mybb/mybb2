<?php

namespace MyBB\Core\Renderers\Post\Quote;

use MyBB\Core\Database\Models\Post;

interface QuoteInterface
{
	/**
	 * @param Post $post
	 *
	 * @return string
	 */
	public function renderFromPost(Post $post);
}
