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
use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use MyBB\Core\Http\Requests\Topic\CreateRequest;
use MyBB\Core\Http\Requests\Topic\ReplyRequest;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicController extends Controller
{
	/** @var ITopicRepository $topicRepository */
	private $topicRepository;
	/** @var IPostRepository $postRepository */
	private $postRepository;
	/** @var IForumRepository $forumRepository */
	private $forumRepository;
	/** @var Guard $guard */
	private $guard;

	/**
	 * @param ITopicRepository $topicRepository Topic repository instance, used to fetch topic details.
	 * @param IPostRepository  $postRepository  Post repository instance, used to fetch post details.
	 * @param IForumRepository $forumRepository Forum repository interface, used to fetch forum details.
	 * @param Guard            $guard           Guard implementation
	 * @param Request          $request         Request implementation
	 */
	public function __construct(
		ITopicRepository $topicRepository,
		IPostRepository $postRepository,
		IForumRepository $forumRepository,
		Guard $guard,
		Request $request
	) {
		parent::__construct($guard, $request);

		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->forumRepository = $forumRepository;
		$this->guard = $guard;
	}

	public function show($slug = '', $id = 0)
	{
		$topic = $this->topicRepository->find($id);

		if(!$topic)
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		Breadcrumbs::setCurrentRoute('topics.show', $topic);

		$this->topicRepository->incrementViewCount($topic);

		$posts = $this->postRepository->allForTopic($topic, true);

		return view('topic.show', compact('topic', 'posts'));
	}

	public function showPost(Store $settings, $slug = '', $id = 0, $postId = 0)
	{
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if(!$post || !$topic || $post['topic_id'] != $topic['id'])
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$postsPerPage = $settings->get('user.posts_per_page', 10);

		$numPost = $this->postRepository->getNumForPost($post, true);

		if(ceil($numPost / $postsPerPage) == 1)
		{
			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id, '#post-' . $post->id]);
		} else
		{
			return redirect()->route('topics.show', [
				'slug' => $topic->slug,
				'id' => $topic->id,
				'page' => ceil($numPost / $postsPerPage),
				'#post-' . $post->id
			]);
		}
	}

	public function last(Store $settings, $slug = '', $id = 0)
	{
		$topic = $this->topicRepository->find($id);

		if(!$topic)
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$postsPerPage = $settings->get('user.posts_per_page', 10);

		$numPost = $this->postRepository->getNumForPost($topic->lastPost, true);

		if(ceil($numPost / $postsPerPage) == 1)
		{
			return redirect()->route('topics.show',
			                         ['slug' => $topic->slug, 'id' => $topic->id, '#post-' . $topic->last_post_id]);
		} else
		{
			return redirect()->route('topics.show', [
				'slug' => $topic->slug,
				'id' => $topic->id,
				'page' => ceil($numPost / $postsPerPage),
				'#post-' . $topic->last_post_id
			]);
		}
	}

	public function reply($slug = '', $id = 0, $postId = 0)
	{
		$message = '';
		$topic = $this->topicRepository->find($id);

		if(!$topic)
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}
		if($postId)
		{
			$post = $this->postRepository->find($postId);
			if(!$post || $post['topic_id'] != $topic['id'])
			{
				throw new NotFoundHttpException(trans('errors.topic_not_found'));
			}

			if($post->user_id == null) {
				if($post->username) {
					$username = $post->username;
				}
				else {
					$username = trans('general.guest');
				}
			}
			else
			{
				$username = $post->author['name'];
			}

			$message = "[quote='".e($username)."' pid='{$post['id']}' dateline='".
					$post['created_at']->getTimestamp()."']\n{$post['content']}\n[/quote]";
		}

		Breadcrumbs::setCurrentRoute('topics.reply', $topic);

		return view('topic.reply', compact('topic', 'message', 'post'));
	}

	public function postReply($slug = '', $id = 0, ReplyRequest $replyRequest)
	{
		/** @var Topic $topic */
		$topic = $this->topicRepository->find($id);

		if(!$topic)
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$post = $this->postRepository->addPostToTopic($topic, [
			'content' => $replyRequest->input('content'),
			'username' => $replyRequest->input('username'),
		]);

		if($post)
		{
			return redirect()->route('topics.last', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return new \Exception(trans('errors.error_creating_post')); // TODO: Redirect back with error...
	}

	public function edit($slug = '', $id = 0, $postId = 0)
	{
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if(!$post || !$topic || $post['topic_id'] != $topic['id'])
		{
			throw new NotFoundHttpException(trans('errors.post_not_found'));
		}

		Breadcrumbs::setCurrentRoute('topics.edit', $topic);

		return view('topic.edit', compact('post', 'topic'));
	}

	public function postEdit($slug = '', $id = 0, $postId = 0, ReplyRequest $replyRequest)
	{
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if(!$post || !$topic || $post['topic_id'] != $topic['id'])
		{
			throw new NotFoundHttpException(trans('errors.post_not_found'));
		}

		$post = $this->postRepository->editPost($post, [
			'content' => $replyRequest->input('content'),
		]);
		if($post['id'] == $topic['first_post_id'])
		{
			$topic = $this->topicRepository->editTopic($topic, [
				'title' => $replyRequest->input('title'),
			]);
		}

		if($post)
		{
			return redirect()->route('topics.showPost',
			                         ['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]);
		}

		return new \Exception('Error editing post'); // TODO: Redirect back with error...
	}

	public function create($forumId)
	{
		$forum = $this->forumRepository->find($forumId);

		if(!$forum)
		{
			throw new NotFoundHttpException(trans('errors.forum_not_found'));
		}

		Breadcrumbs::setCurrentRoute('topics.create', $forum);

		return view('topic.create', compact('forum'));
	}

	public function postCreate($forumId = 0, CreateRequest $createRequest)
	{
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

		if($topic)
		{
			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return new \Exception(trans('errors.error_creating_topic')); // TODO: Redirect back with error...
	}

	public function delete($slug = '', $id = 0, $postId = 0)
	{
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if(!$post || !$topic || $post['topic_id'] != $topic['id'])
		{
			throw new NotFoundHttpException(trans('errors.post_not_found'));
		}


		if($post['id'] == $topic['first_post_id'])
		{
			$this->topicRepository->deleteTopic($topic);

			return redirect()->route('forums.show', ['slug' => $topic->forum['slug'], 'id' => $topic->forum['id']]);
		} else
		{
			$this->postRepository->deletePost($post);
			$topic = $this->postRepository->updateLastPost($topic);

			return redirect()->route('topics.show', ['slug' => $topic['slug'], 'id' => $topic['id']]);
		}

		return new \Exception(trans('errors.error_deleting_topic')); // TODO: Redirect back with error...
	}

	public function restore($slug = '', $id = 0, $postId = 0)
	{
		$topic = $this->topicRepository->find($id);
		$post = $this->postRepository->find($postId);

		if(!$post || !$topic || $post['topic_id'] != $topic['id'] || !$post['deleted_at'] && !$topic['deleted_at'])
		{
			throw new NotFoundHttpException(trans('errors.post_not_found'));
		}

		if($post['id'] == $topic['first_post_id'])
		{
			$this->topicRepository->restoreTopic($topic);
		} else
		{
			$this->postRepository->restorePost($post);
			$topic = $this->postRepository->updateLastPost($topic);
		}
		if($topic)
		{
			return redirect()->route('topics.showPost',
			                         ['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]);
		}

		return new \Exception(trans('errors.error_deleting_topic')); // TODO: Redirect back with error...
	}
}
