<?php

namespace MyBB\Core\Renderers\Post\Quote;

use Illuminate\Foundation\Application;
use MyBB\Core\Database\Models\Post;

class Markdown implements QuoteInterface
{
	/** @var Application */
	private $app;

	/**
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * @param Post $post
	 *
	 * @return string
	 */
	public function renderFromPost(Post $post)
	{
		$post = $this->app->make('MyBB\\Core\\Presenters\\Post', [$post]);
		$message = $post->content;

		// TODO: MarkdownQuoteRenderer
		return "> {$message}";
	}
}
