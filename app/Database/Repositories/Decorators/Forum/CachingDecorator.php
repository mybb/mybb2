<?php
/**
 * Forum repository decorator, providing caching of forums.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Decorators\Forum;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use MyBB\Core\Database\Repositories\IForumRepository;

class CachingDecorator implements IForumRepository
{
	/** @var IForumRepository $decoratedRepository */
	private $decoratedRepository;
	/** @var CacheRepository $cache */
	private $cache;

	public function __construct(IForumRepository $decorated, CacheRepository $cache)
	{
		$this->decoratedRepository = $decorated;
		$this->cache = $cache;
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
	 * @return mixed
	 */
	public function getIndexTree()
	{
		if (($forums = $this->cache->get('forums.index_tree')) == null) {
			$forums = $this->decoratedRepository->getIndexTree();
			$this->cache->forever('forums.index_tree', $forums);
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
		return $this->decoratedRepository->incrementTopicCount($id);
	}
}
