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
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;

class ForumRepository implements ForumRepositoryInterface
{
	/**
	 * @var Forum $forumModel
	 * @access protected
	 */
	protected $forumModel;

	/**
	 * @var PermissionChecker
	 */
	private $permissionChecker;

	/**
	 * @param Forum             $forumModel        The model to use for forums.
	 * @param PermissionChecker $permissionChecker The permission class
	 */
	public function __construct(
		Forum $forumModel,
		PermissionChecker $permissionChecker
	) {
		$this->forumModel = $forumModel;
		$this->permissionChecker = $permissionChecker;
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
		$unviewable = $this->permissionChecker->getUnviewableIdsForContent('forum');

		return $this->forumModel->with(['children', 'parent'])->whereNotIn('id', $unviewable)->find($id);
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
	 * @param bool $checkPermissions
	 *
	 * @return mixed
	 */
	public function getIndexTree($checkPermissions = true)
	{
		$unviewable = $this->permissionChecker->getUnviewableIdsForContent('forum');

		// TODO: The caching decorator would also cache the relations here
		$baseQuery = $this->forumModel->where('parent_id', '=', null);

		if ($checkPermissions) {
			$baseQuery = $baseQuery->whereNotIn('id', $unviewable);
		}

		return $baseQuery->with([
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

	/**
	 * Update the last post for this forum
	 *
	 * @param Forum $forum The forum to update
	 *
	 * @return mixed
	 */

	public function updateLastPost(Forum $forum, Post $post = null)
	{
		if ($post === null) {
			$topic = $forum->topics->sortByDesc('last_post_id')->first();
			if ($topic != null) {
				$post = $post->lastPost;
			}
		}

		if ($post != null) {
			$forum->update([
				'last_post_id' => $post->id,
				'last_post_user_id' => $post->user_id
			]);
		} else {
			$forum->update([
				'last_post_id' => null,
				'last_post_user_id' => null
			]);
		}

		return $forum;
	}
}
