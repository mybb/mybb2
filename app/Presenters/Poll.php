<?php
/**
 * Poll presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Illuminate\Auth\Guard;
use MyBB\Core\Database\Models\Poll as PollModel;
use MyBB\Core\Database\Repositories\IPollVoteRepository;

class Poll extends BasePresenter
{
	/** @var PollModel $wrappedObject */
	protected $wrappedObject;

	/** @var  IPollVoteRepository $pollVoteRepository */
	protected $pollVoteRepository;

	/** @var Guard $guard */
	private $guard;

	/** @var array $cache */
	protected $cache = [];

	/**
	 * @param PollModel           $resource
	 * @param IPollVoteRepository $pollVoteRepository
	 * @param Guard               $guard
	 */
	public function __construct(
		PollModel $resource,
		IPollVoteRepository $pollVoteRepository,
		Guard $guard
	) {
		$this->wrappedObject = $resource;
		$this->pollVoteRepository = $pollVoteRepository;
		$this->guard = $guard;
	}

	public function options()
	{
		if (!isset($this->cache['options'])) {
			$this->cache['options'] = json_decode($this->wrappedObject->options);
			for ($i = 0; $i < count($this->cache['options']); $i++) {
				$this->cache['options'][$i]->voted = false;
			}
			if ($this->myVote()) {
				$votes = explode(',', $this->myVote->vote);
				foreach ($votes as $vote) {
					$this->cache['options'][$vote - 1]->voted = true;
				}
			}
		}

		return $this->cache['options'];
	}

	public function num_votes()
	{
		if (!isset($this->cache['num_votes'])) {
			$options = $this->options();
			$votes = 0;
			foreach ($options as $option) {
				$votes += $option->votes;
			}
			$this->cache['num_votes'] = $votes;
		}

		return $this->cache['num_votes'];
	}

	public function num_options()
	{
		if (!isset($this->cache['num_options'])) {
			$this->cache['num_options'] = count($this->options());
		}

		return $this->cache['num_options'];
	}

	public function is_closed()
	{
		return ($this->wrappedObject->is_closed ||
			($this->wrappedObject->end_at && $this->wrappedObject->end_at < new \DateTime)
		);
	}

	public function end_at()
	{
		if ($this->wrappedObject->end_at) {
			return new \DateTime($this->wrappedObject->end_at);
		}

		return null;
	}

	public function myVote()
	{
		if (!isset($this->cache['myVote'])) {
			if ($this->guard->check()) {
				$this->cache['myVote'] = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $this->wrappedObject);
			} else {
				$this->cache['myVote'] = null;
			}
		}

		return $this->cache['myVote'];
	}
}
