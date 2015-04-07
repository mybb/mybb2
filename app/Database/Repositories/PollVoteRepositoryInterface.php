<?php
/**
 * PollVote repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Poll;
use MyBB\Core\Database\Models\User;

interface PollVoteRepositoryInterface
{

	/**
	 * Find a single vote by ID.
	 *
	 * @param string $id The ID of the vote to find.
	 *
	 * @return mixed
	 */
	public function find($id);

	/**
	 * Create a new vote
	 *
	 * @param array $details Details about the vote.
	 *
	 * @return mixed
	 */
	public function create(array $details = []);

	/**
	 * @param User $user
	 * @param Poll $poll
	 * @return mixed
	 */
	public function findForUserPoll(User $user, Poll $poll);

	/**
	 * @param Poll $poll
	 * @return mixed
	 */
	public function allForPoll(Poll $poll);

	/**
	 * @param Poll $poll
	 * @return mixed
	 */
	public function removeAllByPoll(Poll $poll);
}
