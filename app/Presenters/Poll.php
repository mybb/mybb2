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
use MyBB\Core\Database\Models\Poll as PollModel;
use MyBB\Core\Database\Repositories\IPollRepository;

class Poll extends BasePresenter
{
	/** @var PollModel $wrappedObject */
	protected $wrappedObject;

	/** @var  IPollRepository $pollRepository */
	protected $pollRepository;

	/** @var array $cache */
	protected $cache = [];

	/**
	 * @param PollModel $resource The poll being wrapped by this presenter.
	 */
	public function __construct(PollModel $resource, IPollRepository $pollRepository)
	{
		$this->wrappedObject = $resource;
		$this->pollRepository = $pollRepository;
	}

	public function options()
	{
		if(!isset($this->cache['options'])) {
			$this->cache['options'] = json_decode($this->wrappedObject->options);
		}
		return $this->cache['options'];
	}

	public function num_votes()
	{
		if(!isset($this->cache['num_votes'])) {
			$options = $this->options();
			$votes = 0;
			foreach($options as $option)
			{
				$votes += $option->votes;
			}
			$this->cache['num_votes'] = $votes;
		}
		return $this->cache['num_votes'];
	}

	public function num_options() {
		if(!isset($this->cache['num_options'])) {
			$this->cache['num_options'] = count($this->options());
		}

		return $this->cache['num_options'];
	}

	public function myVote() {

	}
}
