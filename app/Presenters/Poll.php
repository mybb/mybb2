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

class Poll extends BasePresenter
{
	/** @var PollModel $wrappedObject */

	/**
	 * @param PollModel $resource The poll being wrapped by this presenter.
	 */
	public function __construct(PollModel $resource)
	{
		$this->wrappedObject = $resource;
	}

	public function options()
	{
		return json_decode($this->wrappedObject->options);
	}

	public function num_votes()
	{
		$options = $this->options();
		$votes = 0;
		foreach($options as $option)
		{
			$votes += $option->votes;
		}
		return $votes;
	}

	public function author()
	{
		if($this->wrappedObject->user_id == null)
		{
			$user = new UserModel();
			if($this->wrappedObject->username != null)
			{
				$user->name = $this->wrappedObject->username;
			}
			else
			{
				$user->name = trans('general.guest');
			}

			$decoratedUser = app()->make('MyBB\Core\Presenters\User', [$user]);

			return $decoratedUser;
		}

		return $this->wrappedObject->author;
	}
}
