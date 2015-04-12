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

use Breadcrumbs;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Http\Requests\Topic\CreateRequest;
use MyBB\Core\Http\Requests\Topic\ReplyRequest;
use MyBB\Core\Renderers\Post\Quote\QuoteInterface as QuoteRenderer;
use MyBB\Core\Services\TopicDeleter;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class TopicController extends Controller
{
	/** @var TopicRepositoryInterface $topicRepository */
	private $topicRepository;
	/** @var PostRepositoryInterface $postRepository */
	private $postRepository;
	/** @var ForumRepositoryInterface $forumRepository */
	private $forumRepository;
	/** @var PollRepositoryInterface $pollRepository */
	private $pollRepository;
	/** @var Guard $guard */
	private $guard;
	/** @var QuoteRenderer $quoteRenderer */
	private $quoteRenderer;

	/**
	 * @param PostRepositoryInterface  $postRepository  Post repository instance, used to fetch post details.
	 * @param TopicRepositoryInterface $topicRepository Topic repository instance, used to fetch topic details.
	 * @param ForumRepositoryInterface $forumRepository Forum repository interface, used to fetch forum details.
	 * @param PollRepositoryInterface  $pollRepository Poll repository interface, used to fetch poll details.
	 * @param Guard                    $guard           Guard implementation
	 * @param QuoteRenderer            $quoteRenderer
	 */
	public function __construct(
		PostRepositoryInterface $postRepository,
		TopicRepositoryInterface $topicRepository,
		ForumRepositoryInterface $forumRepository,
		PollRepositoryInterface $pollRepository,
		Guard $guard,
		QuoteRenderer $quoteRenderer
	) {
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->forumRepository = $forumRepository;
		$this->pollRepository = $pollRepository;
		$this->guard = $guard;
		$this->quoteRenderer = $quoteRenderer;
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
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$poll = null;

		if ($topic->has_poll) {
			$poll = $topic->poll;
		}

		Breadcrumbs::setCurrentRoute('topics.show', $topic);

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
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
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
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$postsPerPage = $settings->get('user.posts_per_page', 10);

		$numPost = $this->postRepository->getNumForPost($topic->lastPost, true);

		if (ceil($numPost / $postsPerPage) == 1) {
			return redirect()->route('topics.show',
				['slug' => $topic->slug, 'id' => $topic->id, '#post-' . $topic->last_post_id]);
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
     * @param int    $postId
	 *
	 * @return \Illuminate\View\View
	 */
	public function reply($slug = '', $id = 0, Request $request, $postId = 0)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$content = '';
		if ($postId) {
			$post = $this->postRepository->find($postId);
			if (!$post || $post->topic_id != $topic->id) {
				throw new NotFoundHttpException(trans('errors.topic_not_found'));
			}

			$content = $this->quoteRenderer->renderFromPost($post);
		}

		Breadcrumbs::setCurrentRoute('topics.reply', $topic);

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
     * @param string  $slug
     * @param int     $id
     * @param int    $postId
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function replyWithQuote($slug = '', $id = 0, $postId = 0, Request $request)
    {
        return $this->reply($slug, $id, $request, $postId);
    }

	/**
	 * @param string       $slug
	 * @param int          $id
	 * @param ReplyRequest $replyRequest
	 *
	 * @return $this|bool|\Illuminate\Http\RedirectResponse
	 */
	public function postReply($slug = '', $id = 0, ReplyRequest $replyRequest)
	{
		$this->failedValidationRedirect = route('topics.reply', ['slug' => $slug, 'id' => $id]);

		// Forum permissions are checked in "find"
		/** @var Topic $topic */
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
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
			throw new NotFoundHttpException(trans('errors.post_not_found'));
		}

		Breadcrumbs::setCurrentRoute('topics.edit', $topic);

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
	public function postEdit($slug = '', $id = 0, $postId = 0, ReplyRequest $replyRequest)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id']) {
			throw new NotFoundHttpException(trans('errors.post_not_found'));
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
			return redirect()->route('topics.showPost',
				['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]);
		}

		return new \Exception('Error editing post'); // TODO: Redirect back with error...
	}

	/**
	 * @param $forumId
	 *
	 * @return \Illuminate\View\View
	 */
	public function create($forumId)
	{
		// Forum permissions are checked in "find"
		$forum = $this->forumRepository->find($forumId);

		if (!$forum) {
			throw new NotFoundHttpException(trans('errors.forum_not_found'));
		}

		Breadcrumbs::setCurrentRoute('topics.create', $forum);

		return view('topic.create', compact('forum'));
	}

	/**
	 * @param int           $forumId
	 * @param CreateRequest $createRequest
	 *
	 * @return $this|bool|\Illuminate\Http\RedirectResponse
	 */
	public function postCreate($forumId = 0, CreateRequest $createRequest)
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
				'num_options' => count($createRequest->options()),
				'options' => $pollCreateRequest->options(),
				'is_closed' => false,
				'is_multiple' => (bool)$pollCreateRequest->input('is_multiple'),
				'is_public' => (bool)$pollCreateRequest->input('is_public'),
				'end_at' => null,
				'max_options' => $pollCreateRequest->input('maxoptions')
			];
			if ($createRequest->input('endAt')) {
				$poll['end_at'] = new \DateTime($createRequest->input('endAt'));
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
	public function delete($slug = '', $id = 0, $postId = 0, TopicDeleter $topicDeleter)
	{
		// Forum permissions are checked in "find"
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if (!$post || !$topic || $post['topic_id'] != $topic['id']) {
			throw new NotFoundHttpException(trans('errors.post_not_found'));
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
			throw new NotFoundHttpException(trans('errors.post_not_found'));
		}

		if ($post['id'] == $topic['first_post_id']) {
			$this->topicRepository->restoreTopic($topic);
		} else {
			$this->postRepository->restorePost($post);
		}
		if ($topic) {
			return redirect()->route('topics.showPost',
				['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]);
		}

		return new \Exception(trans('errors.error_deleting_topic')); // TODO: Redirect back with error...
	}
}
