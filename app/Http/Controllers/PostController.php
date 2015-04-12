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
use MyBB\Core\Http\Requests\Post\LikePostRequest;
use MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
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
	 * @param PostRepositoryInterface  $postRepository
	 * @param LikesRepositoryInterface $likesRepository
	 * @param Store                    $settings
	 */
	public function __construct(
		PostRepositoryInterface $postRepository,
		LikesRepositoryInterface $likesRepository,
		Store $settings
	) {
		$this->postsRepository = $postRepository;
		$this->likesRepository = $likesRepository;
		$this->settings = $settings;
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
			throw new NotFoundHttpException();
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
			throw new NotFoundHttpException();
		}

		$post->load('topic');

		$likes = $this->likesRepository->getAllLikesForContentPaginated(
			$post,
			$this->settings->get('likes.per_page', 10)
		);

		return view('post.likes', compact('post', 'likes'));
	}
}
