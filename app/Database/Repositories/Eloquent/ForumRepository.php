<?php
/**
 * Forum repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Repositories\IForumRepository;

class ForumRepository implements IForumRepository
{
	/**
	 * @var Forum $forumModel
	 * @access protected
	 */
	protected $forumModel;

	/**
	 * @param Forum $forumModel The model to use for forums.
	 */
	public function __construct(
		Forum $forumModel
	) // TODO: Inject permissions container? So we can check thread permissions before querying?
	{
		$this->forumModel = $forumModel;
	}

	/**
	 * Get all forums.
	 *
	 * @return mixed
	 */
	public function all()
	{
		return $this->forumModel->all();
	}


	/**
	 * Find a single forum by ID.
	 *
	 * @param int $id The ID of the forum to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0)
	{
		return $this->forumModel->with(['children', 'parent'])->find($id);
	}

	/**
	 * Find a single forum by its slug.
	 *
	 * @param string $slug The slug of the forum. Eg: 'my-first-forum'.
	 *
	 * @return mixed
	 */
	public function findBySlug($slug = '')
	{
		return $this->forumModel->whereSlug($slug)->with(['children', 'parent'])->first();
	}

	/**
	 * Get the forum tree for the index, consisting of root forums (categories), and one level of descendants.
	 *
	 * @return mixed
	 */
	public function getIndexTree()
	{
		// TODO: The caching decorator would also cache the relations here
		return $this->forumModel->where('parent_id', '=', null)->with([
			'children',
			'children.lastPost',
			'children.lastPost.topic',
			'children.lastPostAuthor'
		])->get();
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
		$forum = $this->find($id);

		if ($forum) {
			$forum->increment('num_posts');
		}

		return $forum;
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
		$forum = $this->find($id);

		if ($forum) {
			$forum->increment('num_topics');
		}

		return $forum;
	}
}
