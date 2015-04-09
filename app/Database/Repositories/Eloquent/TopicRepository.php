<?php
/**
 * Topic repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Settings\Store;

class TopicRepository implements TopicRepositoryInterface
{
	/**
	 * @var Topic $topicModel
	 * @access protected
	 */
	protected $topicModel;
	/**
	 * @var Guard $guard
	 * @access protected
	 */
	protected $guard;
	/**
	 * @var PostRepositoryInterface $postRepository
	 * @access protected
	 */
	protected $postRepository;

	private $dbManager;
	/**
	 * @var Str $stringUtils
	 * @access protected
	 */
	protected $stringUtils;

	/** @var Store $settings */
	private $settings;

	/** @var ForumRepositoryInterface */
	private $forumRepository;

	/** @var IPollRepository */
	private $pollRepository;

	/** @var PermissionChecker */
	private $permissionChecker;

	/**
	 * @param Topic                    $topicModel The model to use for threads.
	 * @param Guard                    $guard Laravel guard instance, used to get user ID.
	 * @param PostRepositoryInterface  $postRepository Used to manage posts for topics.
	 * @param Str                      $stringUtils String utilities, used for creating slugs.
	 * @param DatabaseManager          $dbManager Database manager, needed to do transactions.
	 * @param Store                    $settings The settings container
	 * @param ForumRepositoryInterface $forumRepository
	 * @param PollRepositoryInterface  $pollRepository
	 * @param PermissionChecker        $permissionChecker
	 */
	public function __construct(
		Topic $topicModel,
		Guard $guard,
		PostRepositoryInterface $postRepository,
		Str $stringUtils,
		DatabaseManager $dbManager,
		Store $settings,
		ForumRepositoryInterface $forumRepository,
		PollRepositoryInterface $pollRepository,
		PermissionChecker $permissionChecker
	) {
		$this->topicModel = $topicModel;
		$this->guard = $guard;
		$this->postRepository = $postRepository;
		$this->stringUtils = $stringUtils;
		$this->dbManager = $dbManager;
		$this->settings = $settings;
		$this->forumRepository = $forumRepository;
		$this->pollRepository = $pollRepository;
		$this->permissionChecker = $permissionChecker;
	}

	/**
	 * Get all threads.
	 *
	 * @return mixed
	 */
	public function all()
	{
		return $this->topicModel->all();
	}

	/**
	 * Increment view count for topic
	 *
	 * @param Topic $topic
	 */
	public function incrementViewCount(Topic $topic)
	{
		$topic->increment('views');
	}

	/**
	 * Get all threads created by a user.
	 *
	 * @param int $userId The ID of the user.
	 *
	 * @return mixed
	 */
	public function allForUser($userId = 0)
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->topicModel->where('user_id', '=', $userId)->whereNotIn('forum_id', $unviewableForums)->get();
	}

	/**
	 * Find a single topic by ID.
	 *
	 * @param int $id The ID of the thread to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0)
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->topicModel->withTrashed()->with(['author'])->whereNotIn('forum_id', $unviewableForums)->find($id);
	}

	/**
	 * Find a single topic by its slug.
	 *
	 * @param string $slug The slug of the thread. Eg: 'my-first-thread'.
	 *
	 * @return mixed
	 */
	public function findBySlug($slug = '')
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->topicModel->withTrashed()->with(['author'])->where('slug', '=', $slug)->whereNotIn('forum_id',
			$unviewableForums)->first();
	}

	/**
	 * @param int $num
	 *
	 * @return mixed
	 */
	public function getNewest($num = 20)
	{
		$unviewableForums = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->topicModel->orderBy('last_post_id', 'desc')->with([
			'lastPost',
			'forum',
			'lastPost.author'
		])->whereNotIn('forum_id', $unviewableForums)->take($num)->get();
	}

	/**
	 * Get all threads within a forum.
	 *
	 * @param Forum  $forum    The forum the threads belong to.
	 * @param string $orderBy  The order by column
	 * @param string $orderDir asc|desc
	 *
	 * @return mixed
	 */
	public function allForForum(Forum $forum, $orderBy = 'posts.created_at', $orderDir = 'desc')
	{
		// Build the correct order_by column - nice versions may be submitted
		switch ($orderBy) {
			case 'replies':
				$orderBy = 'num_posts';
				break;
			case 'startdate':
				$orderBy = 'topics.created_at';
				break;
			case 'lastpost':
			default:
				$orderBy = 'posts.created_at';
				break;
		}

		$topicsPerPage = $this->settings->get('user.topics_per_page', 20);

		return $this->topicModel->withTrashed()->with(['author', 'lastPost', 'lastPost.author'])
			->leftJoin('posts', 'last_post_id', '=', 'posts.id')->where('forum_id', '=', $forum->id)
			->orderBy($orderBy, $orderDir)->paginate($topicsPerPage, ['topics.*']);
	}

	/**
	 * Create a new topic
	 *
	 * @param array $details Details about the topic.
	 *
	 * @return mixed
	 */
	public function create(array $details = [])
	{
		$details = array_merge([
			'title' => '',
			'forum_id' => 0,
			'user_id' => $this->guard->user()->id,
			'username' => null,
			'first_post_id' => 0,
			'last_post_id' => 0,
			'views' => 0,
			'num_posts' => 0,
			'content' => '',
		], $details);

		$details['slug'] = $this->createSlugForTitle($details['title']);

		if ($details['user_id'] > 0) {
			$details['username'] = User::find($details['user_id'])->name; // TODO: Use User Repository!
		} else {
			$details['user_id'] = null;
			if ($details['username'] == trans('general.guest')) {
				$details['username'] = null;
			}
		}

		$topic = null;

		$this->dbManager->transaction(function () use ($details, &$topic) {
			$topic = $this->topicModel->create([
				'title' => $details['title'],
				'slug' => $details['slug'],
				'forum_id' => $details['forum_id'],
				'user_id' => $details['user_id'],
				'username' => $details['username'],
			]);

			$firstPost = $this->postRepository->addPostToTopic($topic, [
				'content' => $details['content'],
				'username' => $details['username'],
			]);

			$topic->update([
				'first_post_id' => $firstPost->id,
				'last_post_id' => $firstPost->id,
				'num_posts' => 1,
			]);
		});

		$topic->forum->increment('num_topics');

		if ($topic->user_id > 0) {
			$topic->author->increment('num_topics');
		}

		return $topic;
	}

	/**
	 * Create a unique slug for a topic title.
	 *
	 * @param string $title The title of the topic.
	 *
	 * @return string The slugged title.
	 */
	private function createSlugForTitle($title = '')
	{
		$title = (string)$title;
		$sluggedTitle = $this->stringUtils->slug($title, '-');

		return $sluggedTitle;
	}

	/**
	 * Edit a topic
	 *
	 * @param Topic $topic        The topic to edit
	 * @param array $topicDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function editTopic(Topic $topic, array $topicDetails)
	{

		$topic->update($topicDetails);

		return $topic;
	}

	/**
	 * Restore a topic
	 *
	 * @param Topic $topic The topic to restore
	 *
	 * @return mixed
	 */
	public function restoreTopic(Topic $topic)
	{
		$topic->forum->increment('num_topics');
		$topic->forum->increment('num_posts', $topic->num_posts);

		$topic->author->increment('num_topics');

		$success = $topic->restore();

		if ($success) {
			if ($topic->last_post_id > $topic->forum->last_post_id) {
				$this->forumRepository->updateLastPost($topic->forum, $topic->lastPost);
			}
		}

		return $success;
	}

	/**
	 * Find a single topic with a specific slug and ID.
	 *
	 * @param string $slug The slug for the topic.
	 * @param int    $id   The ID of the topic to find.
	 *
	 * @return mixed
	 */
	public function findBySlugAndId($slug = '', $id = 0)
	{
		return $this->topicModel->withTrashed()->with(['author'])->where('slug', '=', $slug)->where('id', '=', $id)
			->first();
	}

	/**
	 * Update the last post of the topic
	 *
	 * @param Topic $topic The topic to update
	 *
	 * @return mixed
	 */
	public function updateLastPost(Topic $topic, Post $post = null)
	{
		if ($post === null) {
			$post = $topic->posts->sortByDesc('id')->first();
		}

		$topic->update([
			'last_post_id' => $post->id
		]);

		return $topic;
	}
}
