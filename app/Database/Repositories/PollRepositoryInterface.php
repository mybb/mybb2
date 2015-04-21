<?php
/**
 * Poll repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Poll;
use MyBB\Core\Database\Models\Topic;

interface PollRepositoryInterface
{
	/**
	 * Find a single poll by ID.
	 *
	 * @param string $id The ID of the poll to find.
	 *
	 * @return \MyBB\Core\Database\Models\Poll
	 */
	public function find($id);

	/**
	 * Create a new poll
	 *
	 * @param array $details Details about the poll.
	 *
	 * @return \MyBB\Core\Database\Models\Poll
	 */
	public function create(array $details = []);

	/**
	 * Find poll of a topic
	 *
	 * @param Topic $topic
	 *
	 * @return \MyBB\Core\Database\Models\Poll
	 */
	public function getForTopic(Topic $topic);

	/**
	 * Remove the poll
	 * @param Poll $poll
	 *
	 * @return bool
	 */
	public function remove(Poll $poll);

	/**
	 * Edit a poll
	 *
	 * @param Poll  $poll        The poll to edit
	 * @param array $pollDetails The details of the poll.
	 *
	 * @return Poll
	 */
	public function editPoll(Poll $poll, array $pollDetails);
}
