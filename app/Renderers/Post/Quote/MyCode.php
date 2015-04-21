<?php

namespace MyBB\Core\Renderers\Post\Quote;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Presenters\Post as PostPresenter;

class MyCode implements QuoteInterface
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
	 *
	 * @return string
	 */
	public function renderFromPost(Post $post)
	{
		$post = new PostPresenter($post, $this->guard);
		$message = $post->content;
		$slapUsername = $post->author->name;
		$message = preg_replace(
			'#(>|^|\r|\n)/me ([^\r\n<]*)#i',
			"\\1* {$slapUsername} \\2",
			$message
		);
		$slap = trans('parser::parser.slap');
		$withTrout = trans('parser::parser.withTrout');
		$message = preg_replace(
			'#(>|^|\r|\n)/slap ([^\r\n<]*)#i',
			"\\1* {$slapUsername} {$slap} \\2 {$withTrout}",
			$message
		);
		$message = preg_replace("#\[attachment=([0-9]+?)\]#i", '', $message);

		return "[quote='" . e($post->author->name) . "' pid='{$post->id}' dateline='" .
		$post->created_at->getTimestamp() . "']\n{$message}\n[/quote]";
	}
}
