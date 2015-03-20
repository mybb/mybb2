<?php
/**
 * Poll repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\Poll;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\IPollRepository;

class PollRepository implements IPollRepository
{
	/**
	 * @var Poll $pollModel
	 * @access protected
	 */
	protected $pollModel;
	/**
	 * @var Guard $guard ;
	 * @access protected
	 */
	protected $guard;

	/**
	 * @param Poll			  $pollModel    The model to use for polls.
	 * @param Guard           $guard          Laravel guard instance, used to get user ID.
	 */
	public function __construct(
		Poll $pollModel,
		Guard $guard
	)
	{
		$this->pollModel = $pollModel;
		$this->guard = $guard;
	}

	/**
	 * Find a single poll by ID.
	 *
	 * @param string $id The ID of the poll to find.
	 *
	 * @return mixed
	 */
	public function find($id)
	{
		return $this->pollModel->with(['author', 'topic'])->find($id);
	}

	/**
	 * Create a new poll
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

		if($details['user_id'] <= 0)
		{
			$details['user_id'] = null;
		}

		$poll = $this->pollModel->create($details);
		return $poll;
	}

	/**
	 * Find all poll of a topic
	 *
	 * @param Topic $topic
	 *
	 * @return mixed
	 */
	public function allForTopic(Topic $topic)
	{
		return $this->pollModel->with(['author'])->where('topic_id', $topic->id)->get();
	}
}
