<?php

namespace MyBB\Core\Renderers\Post\Quote;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Presenters\Post as PostPresenter;

class Markdown implements QuoteInterface
{
    /**
     * @var Guard $guard
     */
    private $guard;

    /**
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

	/**
	 * @param Post $post
	 * @return string
	 */
	public function renderFromPost(Post $post)
	{
		$post = new PostPresenter($post, $this->guard);
		$message = $post->content;
		// TODO: MarkdownQuoteRenderer
		return "> {$message}";
	}
}
