<?php
/**
 * Thread presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use Illuminate\View\Factory as ViewFactory;
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
	 * @var ViewFactory
	 */
	protected $viewFactory;

	/**
	 * @param TopicModel $resource The thread being wrapped by this presenter.
	 * @param ModerationRegistry $moderations
	 * @param ViewFactory $viewFactory
	 */
	public function __construct(TopicModel $resource, ModerationRegistry $moderations, ViewFactory $viewFactory)
	{
		$this->wrappedObject = $resource;
		$this->moderations = $moderations;
		$this->viewFactory = $viewFactory;
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
	 * @return \Illuminate\View\View
	 */
	public function moderations()
	{
		return $this->viewFactory->make('partials.moderation.inline_moderations', [
			'moderations' => $this->moderations->getForContent(new Post()),
		]);
	}
}
