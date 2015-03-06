<?php
/**
 * Thread repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Forum;
use MyBB\Core\Database\Models\Topic;

interface ITopicRepository
{
	/**
	 * Get all topics.
	 *
	 * @return mixed
	 */
	public function all();

	/**
	 * Increment view count for a topic.
	 *
	 * @param Topic $topic The topic to increment the view count for.
	 */
	public function incrementViewCount(Topic $topic);

	/**
	 * Get all topics within a forum.
	 *
	 * @param Forum $forum The forum the threads belong to.
	 *
	 * @return mixed
	 */
	public function allForForum(Forum $forum);

	/**
	 * Get all topics created by a user.
	 *
	 * @param int $userId The ID of the user.
	 *
	 * @return mixed
	 */
	public function allForUser($userId = 0);

	/**
	 * Find a single topic by ID.
	 *
	 * @param int $id The ID of the thread to find.
	 *
	 * @return mixed
	 */
	public function find($id = 0);

	/**
	 * Find a single topic by its slug.
	 *
	 * @param string $slug The slug of the thread. Eg: 'my-first-thread'.
	 *
	 * @return mixed
	 */
	public function findBySlug($slug = '');

	/**
	 * Find a single topic with a specific slug and ID.
	 *
	 * @param string $slug The slug for the topic.
	 * @param int    $id   The ID of the topic to find.
	 *
	 * @return mixed
	 */
	public function findBySlugAndId($slug = '', $id = 0);

	/**
	 * Create a new topic
	 *
	 * @param array $details Details about the topic.
	 *
	 * @return mixed
	 */
	public function create(array $details = []);
}
