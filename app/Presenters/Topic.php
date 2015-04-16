<?php
/**
 * Thread presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Support\Collection;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic as TopicModel;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Moderation\ModerationRegistry;

class Topic extends BasePresenter
{
	/** @var TopicModel $wrappedObject */

	/**
	 * @var ModerationRegistry
	 */
	protected $moderations;

	/**
	 * @param TopicModel $resource The thread being wrapped by this presenter.
	 * @param ModerationRegistry $moderations
	 */
	public function __construct(TopicModel $resource, ModerationRegistry $moderations)
	{
		$this->wrappedObject = $resource;
		$this->moderations = $moderations;
	}

	public function replies()
	{
		return $this->wrappedObject->num_posts - 1;
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

	/**
	 * @return \MyBB\Core\Moderation\ModerationInterface[]
	 */
	public function moderations()
	{
		$moderations = $this->moderations->getForContent(new Post());
		$decorated = [];
		$presenter = app()->make('autopresenter');

		foreach ($moderations as $moderation) {
			$decorated[] = $presenter->decorate($moderation);
		}

		return $decorated;
	}
}
