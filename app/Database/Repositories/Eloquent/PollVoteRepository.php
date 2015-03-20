<?php
/**
 * PollVote repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\PollVote;
use MyBB\Core\Database\Repositories\IPollVoteRepository;

class PollVoteRepository implements IPollVoteRepository
{
	/**
	 * @var PollVote $voteModel
	 * @access protected
	 */
	protected $voteModel;
	/**
	 * @var Guard $guard ;
	 * @access protected
	 */
	protected $guard;

	/**
	 * @param PollVote		  $voteModel    The model to use for poll votes.
	 * @param Guard           $guard          Laravel guard instance, used to get user ID.
	 */
	public function __construct(
		PollVote $voteModel,
		Guard $guard
	)
	{
		$this->voteModel = $voteModel;
		$this->guard = $guard;
	}

	/**
	 * Find a single poll vote by ID.
	 *
	 * @param string $id The ID of the vote to find.
	 *
	 * @return mixed
	 */
	public function find($id)
	{
		return $this->voteModel->with(['author', 'poll'])->find($id);
	}

	/**
	 * Create a new poll vote
	 *
	 * @param array $details Details about the poll.
	 *
	 * @return mixed
	 */
	public function create(array $details = [])
	{
		$details = array_merge([
			'user_id' => $this->guard->user()->id,
		], $details);

		if($details['user_id'] < 0)
		{
			$details['user_id'] = null;
		}

		$vote = $this->voteModel->create($details);
		return $vote;
	}
}
