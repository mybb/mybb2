<?php
/**
 * Topic Controller.
 *
 * Used to view, create, delete and update topics.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Controllers;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Exceptions\ForumNotFoundException;
use MyBB\Core\Exceptions\PostNotFoundException;
use MyBB\Core\Exceptions\TopicNotFoundException;
use MyBB\Core\Http\Requests\Topic\CreateRequest;
use MyBB\Core\Http\Requests\Topic\ReplyRequest;
use MyBB\Core\Renderers\Post\Quote\QuoteInterface as QuoteRenderer;
use MyBB\Core\Services\TopicDeleter;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicController extends AbstractController
{
	/**
	 * @var TopicRepositoryInterface
	 */
	private $topicRepository;

	/**
	 * @var PostRepositoryInterface
	 */
	private $postRepository;

	/**
	 * @var ForumRepositoryInterface
	 */
	private $forumRepository;

	/**
	 * @var PollRepositoryInterface
	 */
	private $pollRepository;

	/**
	 * @var Guard
	 */
	private $guard;

	/**
	 * @var QuoteRenderer
	 */
	private $quoteRenderer;

	/**
	 * @var Breadcrumbs
	 */
	private $breadcrumbs;

	/**
	 * @param PostRepositoryInterface  $postRepository  Post repository instance, used to fetch post details.
	 * @param TopicRepositoryInterface $topicRepository Topic repository instance, used to fetch topic details.
	 * @param ForumRepositoryInterface $forumRepository Forum repository interface, used to fetch forum details.
	 * @param PollRepositoryInterface  $pollRepository  Poll repository interface, used to fetch poll details.
	 * @param Guard                    $guard           Guard implementation
	 * @param QuoteRenderer            $quoteRenderer
	 * @param Breadcrumbs              $breadcrumbs
	 */
	public function __construct(
		PostRepositoryInterface $postRepository,
		TopicRepositoryInterface $topicRepository,
		ForumRepositoryInterface $forumRepository,
		PollRepositoryInterface $pollRepository,
		Guard $guard,
		QuoteRenderer $quoteRenderer,
		Breadcrumbs $breadcrumbs
	) {
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->forumRepository = $forumRepository;
		$this->pollRepository = $pollRepository;
		$this->guard = $guard;
		$this->quoteRenderer = $quoteRenderer;
		$this->breadcrumbs = $breadcrumbs;
	}

	/**
	 * @param string $slug
	 * @param int    $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function show($slug = '', $id = 0)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new TopicNotFoundException;
		}

		$poll = null;

		if ($topic->has_poll) {
			$poll = $topic->poll;
		}

		$this->breadcrumbs->setCurrentRoute('topics.show', $topic);

		$this->topicRepository->incrementViewCount($topic);

		$posts = $this->postRepository->allForTopic($topic, true);

		return view('topic.show', compact('topic', 'posts', 'poll'));
	}

	/**
	 * @param Store  $settings
	 * @param string $slug
	 * @param int    $id
	 * @param int    $postId
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function showPost(Store $settings, $slug = '', $id = 0, $postId = 0)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id']) {
			throw new TopicNotFoundException;
		}

		$postsPerPage = $settings->get('user.posts_per_page', 10);

		$numPost = $this->postRepository->getNumForPost($post, true);

		if (ceil($numPost / $postsPerPage) == 1) {
			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id, '#post-' . $post->id]);
		} else {
			return redirect()->route('topics.show', [
				'slug' => $topic->slug,
				'id' => $topic->id,
				'page' => ceil($numPost / $postsPerPage),
				'#post-' . $post->id
			]);
		}
	}

	/**
	 * @param Store  $settings
	 * @param string $slug
	 * @param int    $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function last(Store $settings, $slug = '', $id = 0)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new TopicNotFoundException;
		}

		$postsPerPage = $settings->get('user.posts_per_page', 10);

		$numPost = $this->postRepository->getNumForPost($topic->lastPost, true);

		if (ceil($numPost / $postsPerPage) == 1) {
			return redirect()->route(
				'topics.show',
				['slug' => $topic->slug, 'id' => $topic->id, '#post-' . $topic->last_post_id]
			);
		} else {
			return redirect()->route('topics.show', [
				'slug' => $topic->slug,
				'id' => $topic->id,
				'page' => ceil($numPost / $postsPerPage),
				'#post-' . $topic->last_post_id
			]);
		}
	}

	/**
	 * @param string  $slug
	 * @param int     $id
	 * @param Request $request
	 * @param null    $postId
	 *
	 * @return \Illuminate\View\View
	 */
	public function reply($slug, $id, Request $request, $postId = null)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new TopicNotFoundException;
		}

		$content = '';
		if ($postId) {
			$post = $this->postRepository->find($postId);
			if (!$post || $post->topic_id != $topic->id) {
				throw new TopicNotFoundException;
			}

			$content = $this->quoteRenderer->renderFromPost($post);
		}

		$this->breadcrumbs->setCurrentRoute('topics.reply', $topic);

		$username = trans('general.guest');
		if ($request->has('content')) {
			$content = $request->get('content');
		}
		if ($request->has('username')) {
			$username = $request->get('username');
		}

		return view('topic.reply', compact('topic', 'content', 'username'));
	}

	/**
	 * @param string       $slug
	 * @param int          $id
	 * @param ReplyRequest $replyRequest
	 *
	 * @return $this|bool|\Illuminate\Http\RedirectResponse
	 */
	public function postReply($slug, $id, ReplyRequest $replyRequest)
	{
		$this->failedValidationRedirect = route('topics.reply', ['slug' => $slug, 'id' => $id]);

		// Forum permissions are checked in "find"
		/** @var Topic $topic */
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new TopicNotFoundException;
		}

		if (!$this->guard->check()) {
			$captcha = $this->checkCaptcha();
			if ($captcha !== true) {
				return $captcha;
			}
		}

		$post = $this->postRepository->addPostToTopic($topic, [
			'content' => $replyRequest->input('content'),
			'username' => $replyRequest->input('username'),
		]);

		if ($post) {
			return redirect()->route('topics.last', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return redirect()->route('topic.reply', ['slug' => $topic->slug, 'id' => $topic->id])->withInput()->withErrors([
			'content' => trans('errors.error_creating_post')
		]);
	}

	/**
	 * @param string $slug
	 * @param int    $id
	 * @param int    $postId
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit($slug = '', $id = 0, $postId = 0)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id']) {
			throw new PostNotFoundException;
		}

		$this->breadcrumbs->setCurrentRoute('topics.edit', $topic);

		return view('topic.edit', compact('post', 'topic'));
	}

	/**
	 * @param string       $slug
	 * @param int          $id
	 * @param int          $postId
	 * @param ReplyRequest $replyRequest
	 *
	 * @return \Exception|\Illuminate\Http\RedirectResponse
	 */
	public function postEdit($slug, $id, $postId, ReplyRequest $replyRequest)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id']) {
			throw new PostNotFoundException;
		}

		$post = $this->postRepository->editPost($post, [
			'content' => $replyRequest->input('content'),
		]);
		if ($post['id'] == $topic['first_post_id']) {
			$topic = $this->topicRepository->editTopic($topic, [
				'title' => $replyRequest->input('title'),
			]);
		}

		if ($post) {
			return redirect()->route(
				'topics.showPost',
				['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]
			);
		}

		return redirect()->route(
			'topic.edit',
			['slug' => $slug, 'id' => $id, 'postId' => $postId]
		)->withInput()->withErrors(['Error editing post']);
	}

	/**
	 * @param int $forumId
	 *
	 * @return \Illuminate\View\View
	 */
	public function create($forumId)
	{
		// Forum permissions are checked in "find"
		$forum = $this->forumRepository->find($forumId);

		if (!$forum) {
			throw new ForumNotFoundException;
		}

		$this->breadcrumbs->setCurrentRoute('topics.create', $forum);

		return view('topic.create', compact('forum'));
	}

	/**
	 * @param int           $forumId
	 * @param CreateRequest $createRequest
	 *
	 * @return $this|bool|\Illuminate\Http\RedirectResponse
	 */
	public function postCreate($forumId, CreateRequest $createRequest)
	{
		// Forum permissions are checked in "CreateRequest"

		if (!$this->guard->check()) {
			$captcha = $this->checkCaptcha();
			if ($captcha !== true) {
				return $captcha;
			}
		}

		$poll = null;
		if ($createRequest->input('add-poll')) {
			$pollCreateRequest = app()->make('MyBB\\Core\\Http\\Requests\\Poll\\CreateRequest');

			$poll = [
				'question' => $pollCreateRequest->input('question'),
				'num_options' => count($pollCreateRequest->options()),
				'options' => $pollCreateRequest->options(),
				'is_closed' => false,
				'is_multiple' => (bool)$pollCreateRequest->input('is_multiple'),
				'is_public' => (bool)$pollCreateRequest->input('is_public'),
				'end_at' => null,
				'max_options' => (int)$pollCreateRequest->input('maxoptions')
			];
			if ($pollCreateRequest->input('endAt')) {
				$poll['end_at'] = new \DateTime($pollCreateRequest->input('endAt'));
			}

		}
		$topic = $this->topicRepository->create([
			'title' => $createRequest->input('title'),
			'forum_id' => $createRequest->input('forum_id'),
			'first_post_id' => 0,
			'last_post_id' => 0,
			'views' => 0,
			'num_posts' => 0,
			'content' => $createRequest->input('content'),
			'username' => $createRequest->input('username'),
		]);

		if ($topic) {
			if ($poll) {
				$poll['topic_id'] = $topic->id;
				$this->pollRepository->create($poll);
				$this->topicRepository->setHasPoll($topic, true);
			}

			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return redirect()->route('topic.create', ['forumId' => $forumId])->withInput()->withErrors([
			'content' => trans('errors.error_creating_topic')
		]);
	}

	/**
	 * @param string       $slug
	 * @param int          $id
	 * @param int          $postId
	 * @param TopicDeleter $topicDeleter
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($slug, $id, $postId, TopicDeleter $topicDeleter)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id']) {
			throw new PostNotFoundException;
		}


		if ($post['id'] == $topic['first_post_id']) {
			$topicDeleter->deleteTopic($topic);

			return redirect()->route('forums.show', ['slug' => $topic->forum['slug'], 'id' => $topic->forum['id']]);
		} else {
			$this->postRepository->deletePost($post);

			return redirect()->route('topics.show', ['slug' => $topic['slug'], 'id' => $topic['id']]);
		}
	}

	/**
	 * @param string $slug
	 * @param int    $id
	 * @param int    $postId
	 *
	 * @return \Exception|\Illuminate\Http\RedirectResponse
	 */
	public function restore($slug = '', $id = 0, $postId = 0)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id'] || !$post['deleted_at'] && !$topic['deleted_at']) {
			throw new PostNotFoundException;
		}

		if ($post['id'] == $topic['first_post_id']) {
			$this->topicRepository->restoreTopic($topic);
		} else {
			$this->postRepository->restorePost($post);
		}
		if ($topic) {
			return redirect()->route(
				'topics.showPost',
				['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]
			);
		}

		return redirect()->route('topics.showPost', ['slug' => $slug, 'id' => $id, 'postId' => $postId])
			->withErrors([trans('errors.error_deleting_topic')]);
	}
}
