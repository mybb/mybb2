<?php
/**
 * Controller to handle requests operating against single posts.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Exceptions\PostNotFoundException;
use MyBB\Core\Http\Requests\Post\LikePostRequest;
use MyBB\Core\Http\Requests\Post\QuotePostRequest;
use MyBB\Core\Renderers\Post\Quote\QuoteInterface as QuoteRenderer;
use MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends AbstractController
{
	/**
	 * @var PostRepositoryInterface $postsRepository
	 */
	private $postsRepository;
	/**
	 * @var LikesRepositoryInterface $likesRepository
	 */
	private $likesRepository;
	/**
	 * @var Store $settings
	 */
	private $settings;

	/**
	 * @var QuoteRenderer $quoteRenderer
	 */
	private $quoteRenderer;

	/**
	 * @param PostRepositoryInterface  $postRepository
	 * @param LikesRepositoryInterface $likesRepository
	 * @param Store                    $settings
	 * @param QuoteRenderer            $quoteRenderer
	 */
	public function __construct(
		PostRepositoryInterface $postRepository,
		LikesRepositoryInterface $likesRepository,
		Store $settings,
		QuoteRenderer $quoteRenderer
	) {
		$this->postsRepository = $postRepository;
		$this->likesRepository = $likesRepository;
		$this->settings = $settings;
		$this->quoteRenderer = $quoteRenderer;
	}

	/**
	 * Handler for POST requests to add a like for a post.
	 *
	 * @param LikePostRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postToggleLike(LikePostRequest $request)
	{
		$post = $this->postsRepository->find($request->get('post_id'));

		if (!$post) {
			throw new PostNotFoundException;
		}

		$like = $this->likesRepository->toggleLikeForContent($post);

		$redirect = redirect(
			route(
				'topics.showPost',
				[
					'slug' => $post->topic->slug,
					'id' => $post->topic_id,
					'postId' => $post->id,
				]
			)
		);

		if ($like === null) {
			$redirect = $redirect->withSuccess(trans('post.like_removed'));
		} else {
			$redirect = $redirect->withSuccess(trans('post.like_added'));
		}

		return $redirect;
	}

	/**
	 * SHow all of the likes a post has received.
	 *
	 * @param int $postId The ID of the post to show the likes for.
	 *
	 * @return \Illuminate\View\View
	 */
	public function getPostLikes($postId)
	{
		$post = $this->postsRepository->find($postId);

		if (!$post) {
			throw new PostNotFoundException;
		}

		$post->load('topic');

		$likes = $this->likesRepository->getAllLikesForContentPaginated(
			$post,
			$this->settings->get('likes.per_page', 10)
		);

		return view('post.likes', compact('post', 'likes'));
	}

	/**
	 * @param QuotePostRequest $quoteRequest
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function postQuotes(QuotePostRequest $quoteRequest)
	{
		$contents = $quoteRequest->input('posts');
		$data = $posts = $conversations = []; //TODO: conversations
		foreach ($contents as $content) {
			if (is_array($content)) {
				$data[] = [(string)$content['id'], $content['data']]; // It isn't XSS, we parsed it with JS.
				$content = $content['id'];
			} else {
				$content = (string)$content;
				$data[] = [$content, ''];
			}
			$content = explode('_', $content);
			switch ($content[0]) {
				case 'post':
					$posts[] = (int)$content[1];
					break;
				case 'conversation':
					$conversations[] = (int)$content[1];
					break;
			}
		}
		$myPosts = $this->postsRepository->getPostsByIds($posts);
		$posts = [];

		$content = "";

		foreach ($myPosts as $post) {
			$posts[$post->id] = $post;
		}

		foreach ($data as $value) {
			list($type, $id) = explode('_', $value[0]);
			$value = $value[1];

			switch ($type) {
				case 'post':
					$post = $posts[$id];
					if ($value) {
						$oldContent = $post->content;
						$oldContentParsed = $post->content_parsed;
						$post->content = $post->content_parsed = $value;
					}
					$content .= $this->quoteRenderer->renderFromPost($post);
					$post->content = $oldContent;
					$post->content_parsed = $oldContentParsed;
					break;
				case 'conversation':
					// TODO
					break;
			}
		}

		return response()->json([
			'message' => $content
		]);
	}
}
