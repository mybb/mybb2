<?php
/**
 * Post repository implementation, using Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Support\Collection;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Likes\Database\Repositories\Eloquent\LikesRepository;
use MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Parser\MessageFormatter;
use MyBB\Settings\Store;

class PostRepository implements PostRepositoryInterface
{
	/**
	 * @var Post $postModel
	 */
	protected $postModel;
	/**
	 * @var Guard $guard
	 */
	protected $guard;
	/**
	 * @var MessageFormatter $formatter
	 */
	protected $formatter;

	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * @var ForumRepositoryInterface
	 */
	private $forumRepository;

	/**
	 * @var PermissionChecker
	 */
	private $permissionChecker;

	/**
	 * @var LikesRepositoryInterface
	 */
	private $likesRepository;

	/**
	 * @param Post                     $postModel         The model to use for posts.
	 * @param Guard                    $guard             Laravel guard instance, used to get user ID.
	 * @param MessageFormatter         $formatter         Post formatter instance.
	 * @param Store                    $settings          The settings container
	 * @param ForumRepositoryInterface $forumRepository
	 * @param PermissionChecker        $permissionChecker
	 * @param LikesRepositoryInterface $likesRepository
	 */
	public function __construct(
		Post $postModel,
		Guard $guard,
		MessageFormatter $formatter,
		Store $settings,
		ForumRepositoryInterface $forumRepository,
		PermissionChecker $permissionChecker,
		LikesRepositoryInterface $likesRepository
	) {
		$this->postModel = $postModel;
		$this->guard = $guard;
		$this->formatter = $formatter;
		$this->settings = $settings;
		$this->forumRepository = $forumRepository;
		$this->permissionChecker = $permissionChecker;
		$this->likesRepository = $likesRepository;
	}

	/**
	 * Find all posts created by a user.
	 *
	 * @param int $userId The ID of the user to get the posts for.
	 *
	 * @return mixed
	 */
	public function allForUser($userId = 0)
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->postModel->where('user_id', '=', $userId)->whereNotIn('topic.forum_id', $unviewableForums)->get();
	}

	/**
	 * @param int $num
	 *
	 * @return mixed
	 */
	public function getNewest($num = 20)
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->postModel->orderBy('created_at', 'desc')->with([
			'topic',
			'topic.forum',
			'author'
		])->whereNotIn('topic.forum_id', $unviewableForums)->take($num)->get();
	}

	/**
	 * Find a single post by its ID.
	 *
	 * @param int $id The ID of the post to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0)
	{
		return $this->postModel->with(['likes', 'author'])->withTrashed()->find($id);
	}

	/**
	 * Get all posts for a thread.
	 *
	 * @param Topic $topic       The thread to fetch the posts for.
	 * @param bool  $withTrashed Find trashed posts?
	 *
	 * @return mixed
	 */
	public function allForTopic(Topic $topic, $withTrashed = false)
	{
		$postsPerPage = $this->settings->get('user.posts_per_page', 10);

		$baseQuery = $this->postModel->with(['author', 'likes'])->where('topic_id', '=', $topic->id);

		if ($withTrashed) {
			$baseQuery = $baseQuery->withTrashed();
		}

		return $baseQuery->paginate($postsPerPage);
	}

	/**
	 * Get's the number of this post in the thread. Eg '1' for the first, '2' for the second etc
	 *
	 * @param Post $post        The post we want the number for
	 * @param bool $withTrashed Count trashed posts?
	 *
	 * @return int
	 */
	public function getNumForPost(Post $post, $withTrashed = false)
	{
		// Get all posts in this thread created before this one...
		$baseQuery = $this->postModel->where('topic_id', '=', $post->topic_id)
			->where('created_at', '<', $post->created_at);

		if ($withTrashed) {
			$baseQuery = $baseQuery->withTrashed();
		}

		// ... and add 1 (the first post doesn't have one created before it etc)
		return $baseQuery->count() + 1;
	}

	/**
	 * Add a post to a topic.
	 *
	 * @param Topic $topic       The topic to add a post to.
	 * @param array $postDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function addPostToTopic(Topic $topic, array $postDetails)
	{

		$postDetails = array_merge([
			'user_id' => $this->guard->user()->id,
			'username' => null,
			'content' => '',
			'content_parsed' => '',
		], $postDetails);

		$postDetails['content_parsed'] = $this->formatter->parse($postDetails['content'], [
			MessageFormatter::ME_USERNAME => $this->guard->user()->name,
		]); // TODO: Parser options...

		if ($postDetails['user_id'] > 0) {
			$postDetails['username'] = User::find($postDetails['user_id'])->name;
		} else {
			$postDetails['user_id'] = null;
			if ($postDetails['username'] == trans('general.guest')) {
				$postDetails['username'] = null;
			}
		}

		$post = $topic->posts()->create($postDetails);

		if ($post !== false) {
			$topic->increment('num_posts');
			$topic->update([
				'last_post_id' => $post['id']
			]);
			$topic->forum->increment('num_posts');
			$topic->forum->update([
				'last_post_id' => $post->id,
				'last_post_user_id' => $postDetails['user_id']
			]);
		}

		if ($post->user_id > 0) {
			$post->author->increment('num_posts');
		}

		return $post;
	}

	/**
	 * Edit a post
	 *
	 * @param Post  $post        The post to edit
	 * @param array $postDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function editPost(Post $post, array $postDetails)
	{
		if ($postDetails['content']) {
			$options = [];
			if ($post->user_id > 0) {
				$options[MessageFormatter::ME_USERNAME] = $post->author->name;
			} else {
				$options[MessageFormatter::ME_USERNAME] = trans('general.guest');
			}

			$postDetails['content_parsed'] = $this->formatter->parse(
				$postDetails['content'],
				$options
			); // TODO: Parser options...
		}

		$post->update($postDetails);

		return $post;
	}

	/**
	 * Deletes all posts for a specific topic
	 *
	 * @param Topic $topic
	 *
	 * @return mixed
	 */
	public function deletePostsForTopic(Topic $topic)
	{
		$baseQuery = $this->postModel->where('topic_id', '=', $topic->id);

		$posts = $baseQuery->get();
		foreach ($posts as $post) {
			$this->likesRepository->removeLikesForContent($post);
		}

		return $baseQuery->forceDelete();
	}

	/**
	 * Delete a post
	 *
	 * @param Post $post The post to delete
	 *
	 * @return mixed
	 */
	public function deletePost(Post $post)
	{
		if ($post['deleted_at'] == null) {
			// Update counters
			$post->topic->decrement('num_posts');
			$post->topic->forum->decrement('num_posts');

			if ($post->user_id > 0) {
				$post->author->decrement('num_posts');
			}

			// Delete the post
			$success = $post->delete();

			if ($success) {
				if ($post->topic->last_post_id == $post->id) {
					$post->topic->update([
						'last_post_id' => $post->topic->posts->sortByDesc('id')->first()->id
					]);
				}

				if ($post->topic->forum->last_post_id == $post->id) {
					$this->forumRepository->updateLastPost($post->topic->forum);
				}
			}

			return $success;
		} else {
			$this->likesRepository->removeLikesForContent($post);
			return $post->forceDelete();
		}
	}

	/**
	 * Restore a post
	 *
	 * @param Post $post The post to restore
	 *
	 * @return mixed
	 */
	public function restorePost(Post $post)
	{
		$post->topic->increment('num_posts');
		$post->topic->forum->increment('num_posts');

		if ($post->user_id > 0) {
			$post->author->increment('num_posts');
		}

		$success = $post->restore();

		if ($success) {
			if ($post->id > $post->topic->last_post_id) {
				$post->topic->update([
					'last_post_id' => $post->id
				]);
			}

			if ($post->id > $post->topic->forum->last_post_id) {
				$this->forumRepository->updateLastPost($post->topic->forum, $post);
			}
		}

		return $success;
	}

	/**
	 * @param array $postIds
	 *
	 * @return mixed
	 */
	public function getPostsByIds(array $postIds)
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->postModel
			->whereIn('id', $postIds)
			->whereHas('topic', function ($query) use ($unviewableForums) {
				$query->whereNotIn('forum_id', $unviewableForums);
			})->get();
	}

	/**
	 * @param Post[] $posts
	 *
	 * @return Post
	 */
	public function mergePosts(array $posts)
	{
		if (! is_array_of($posts, 'MyBB\Core\Database\Models\Post')) {
			throw new \InvalidArgumentException('$posts must be an array of Post objects');
		}

		$collection = new Collection($posts);
		$collection = $collection->sortBy('created_at');

		$firstPost = $collection->shift();
		$firstPostContent = $firstPost->content;

		foreach ($collection as $post) {
			if ($post->author->id !== $firstPost->author->id) {
				throw new \InvalidArgumentException("All posts being merged must have the same author");
			}

			$firstPostContent .= "\n[hr]\n". $post->content;
			$this->deletePost($post);
		}

		$firstPost->content = $firstPostContent;
		$firstPost->content_parsed = $this->formatter->parse($firstPost->content);
		$firstPost->save();

		return $firstPost;
	}
}
