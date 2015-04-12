<?php
/**
 * Forum presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Forum as ForumModel;
use MyBB\Core\Database\Models\User as UserModel;

class Forum extends BasePresenter
{
	/** @var ForumModel $wrappedObject */

	/**
	 * @param ForumModel $resource The forum being wrapped by this presenter.
	 */
	public function __construct(ForumModel $resource)
	{
		$this->wrappedObject = $resource;
	}

	public function lastPostAuthor()
	{
		if($this->wrappedObject->last_post_user_id == null)
		{
			$user = new UserModel();
			$user->id = 0;
			if($this->wrappedObject->lastPost->username != null)
			{
				$user->name = $this->wrappedObject->lastPost->username;
			} else
			{
				$user->name = trans('general.guest');
			}

			$decoratedUser = app()->make('MyBB\Core\Presenters\User', [$user]);

			return $decoratedUser;
		}

		return $this->wrappedObject->lastPostAuthor;
	}

	/**
	 * @return bool
	 */
	public function hasLastPost()
	{
		if ($this->wrappedObject->lastPost == null) {
			return false;
		}

		return true;
	}
}
