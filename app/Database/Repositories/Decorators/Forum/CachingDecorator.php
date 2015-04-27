<?php
/**
 * Forum repository decorator, providing caching of forums.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Decorators\Forum;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Collection;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;

class CachingDecorator implements ForumRepositoryInterface
{
	/**
	 * @var ForumRepositoryInterface
	 */
	private $decoratedRepository;

	/**
	 * @var CacheRepository
	 */
	private $cache;

	/**
	 * @var PermissionChecker
	 */
	private $permissionChecker;

	/**
	 * @var Guard
	 */
	private $guard;

	/**
	 * @param ForumRepositoryInterface $decorated
	 * @param CacheRepository          $cache
	 * @param PermissionChecker        $permissionChecker
	 * @param Guard                    $guard
	 */
	public function __construct(
		ForumRepositoryInterface $decorated,
		CacheRepository $cache,
		PermissionChecker $permissionChecker,
		Guard $guard
	) {
		$this->decoratedRepository = $decorated;
		$this->cache = $cache;
		$this->permissionChecker = $permissionChecker;
		$this->guard = $guard;
	}

	/**
	 * Get all forums.
	 *
	 * @return mixed
	 */
	public function all()
	{
		if (($forums = $this->cache->get('forums.all')) == null) {
			$forums = $this->decoratedRepository->all();
			$this->cache->forever('forums.all', $forums);
		}

		return $forums;
	}

	/**
	 * Get a single forum by ID.
	 *
	 * @param int $id The ID of the forum.
	 *
	 * @return mixed
	 */
	public function find($id = 0)
	{
		return $this->decoratedRepository->find($id);
	}

	/**
	 * Get a single forum by slug (name, sluggified, eg: 'my-first-forum').
	 *
	 * @param string $slug The slug for the forum.
	 *
	 * @return mixed
	 */
	public function findBySlug($slug = '')
	{
		return $this->decoratedRepository->findBySlug($slug);
	}

	/**
	 * Get the forum tree for the index, consisting of root forums (categories), and one level of descendants.
	 *
	 * @param bool $checkPermissions
	 *
	 * @return mixed
	 */
	public function getIndexTree($checkPermissions = true)
	{
		if (($forums = $this->cache->get('forums.index_tree')) == null) {
			$forums = $this->decoratedRepository->getIndexTree(false);
			$this->cache->forever('forums.index_tree', $forums);
		}

		if ($checkPermissions) {
			$forums = $this->filterUnviewableForums($forums);
		}

		return $forums;
	}

	/**
	 * Increment the number of posts in the forum by one.
	 *
	 * @param int $id The ID of the forum to increment the post count for.
	 *
	 * @return mixed
	 */
	public function incrementPostCount($id = 0)
	{
		// TODO: It'd be better to update the cache instead of forgetting it
		$this->cache->forget('forums.index_tree');
		$this->cache->forget('forums.all');

		return $this->decoratedRepository->incrementPostCount($id);
	}

	/**
	 * Increment the number of topics in the forum by one.
	 *
	 * @param int $id The ID of the forum to increment the topic count for.
	 *
	 * @return mixed
	 */
	public function incrementTopicCount($id = 0)
	{
		// TODO: It'd be better to update the cache instead of forgetting it
		$this->cache->forget('forums.index_tree');
		$this->cache->forget('forums.all');

		return $this->decoratedRepository->incrementTopicCount($id);
	}

	/**
	 * Update the last post for this forum
	 *
	 * @param Forum $forum The forum to update
	 * @param Post  $post
	 *
	 * @return void
	 */
	public function updateLastPost(Forum $forum, Post $post = null)
	{
		// TODO: It'd be better to update the cache instead of forgetting it
		$this->cache->forget('forums.index_tree');
		$this->cache->forget('forums.all');
		$this->decoratedRepository->updateLastPost($forum, $post);
	}

	/**
	 * Filters a forum collection by the "canView" permission
	 *
	 * @param Collection $forums
	 *
	 * @return Collection
	 */
	private function filterUnviewableForums(Collection $forums)
	{
		return $forums->filter(function (Forum $forum) {
			return $this->permissionChecker->hasPermission(
				'forum',
				$forum->getContentId(),
				$forum::getViewablePermission(),
				$this->guard->user()
			);
		});
	}

	/**
	 * @param Topic $topic
	 * @param Forum $forum
	 */
	public function moveTopicToForum(Topic $topic, Forum $forum)
	{
		return $this->decoratedRepository->moveTopicToForum($topic, $forum);
	}
}
