<?php
/**
 * Post repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Models\Post;

interface PostRepositoryInterface
{
	/**
	 * Find all posts created by a user.
	 *
	 * @param int $userId The ID of the user to get the posts for.
	 *
	 * @return mixed
	 */
	public function allForUser($userId = 0);

	/**
	 * Get all posts for a thread.
	 *
	 * @param Topic $topic The thread to fetch the posts for.
	 *
	 * @return mixed
	 */
	public function allForTopic(Topic $topic);

	/**
	 * Get the newest posts
	 *
	 * @param int $num The number of posts to return
	 *
	 * @return mixed
	 */
	public function getNewest($num = 20);

	/**
	 * Find a single post by its ID.
	 *
	 * @param int $id The ID of the post to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0);

	/**
	 * Get's the number of this post in the thread. Eg '1' for the first, '2' for the second etc
	 *
	 * @param Post $post        The post we want the number for
	 * @param bool $withTrashed Count trashed posts?
	 *
	 * @return int
	 */
	public function getNumForPost(Post $post, $withTrashed = false);

	/**
	 * Add a post to a topic.
	 *
	 * @param Topic $topic       The topic to add a post to.
	 * @param array $postDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function addPostToTopic(Topic $topic, array $postDetails);

	/**
	 * Edit a post
	 *
	 * @param Post  $post        The post to edit
	 * @param array $postDetails The details of the post to add.
	 *
	 * @return mixed
	 */
	public function editPost(Post $post, array $postDetails);

	/**
	 * Delete a post
	 *
	 * @param Post $post The post to delete
	 *
	 * @return mixed
	 */

	public function deletePost(Post $post);

	/**
	 * Restore a post
	 *
	 * @param Post $post The post to restore
	 *
	 * @return mixed
	 */
	public function restorePost(Post $post);

	/**
	 * Deletes all posts for a specific topic
	 *
	 * @param Topic $topic
	 *
	 * @return mixed
	 */
	public function deletePostsForTopic(Topic $topic);

	/**
	 * @param Post[] $posts
	 *
	 * @return Post
	 */
	public function mergePosts(array $posts);
}
