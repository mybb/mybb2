<?php
/**
 * PollVote repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
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
	 * @return \MyBB\Core\Database\Models\PollVote
	 */
	public function find($id);

	/**
	 * Create a new vote
	 *
	 * @param array $details Details about the vote.
	 *
	 * @return \MyBB\Core\Database\Models\PollVote
	 */
	public function create(array $details = []);

	/**
	 * @param User $user
	 * @param Poll $poll
	 *
	 * @return \MyBB\Core\Database\Models\PollVote
	 */
	public function findForUserPoll(User $user, Poll $poll);

	/**
	 * @param Poll $poll
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function allForPoll(Poll $poll);

	/**
	 * @param Poll $poll
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function removeAllByPoll(Poll $poll);
}
