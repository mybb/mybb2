<?php
/**
 * Post presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Post as PostModel;
use MyBB\Core\Database\Models\User as UserModel;

class Post extends BasePresenter
{
	/** @var PostModel $wrappedObject */

	/**
	 * @param PostModel $resource The post being wrapped by this presenter.
	 */
	public function __construct(PostModel $resource)
	{
		$this->wrappedObject = $resource;
	}

	public function author()
	{
		if($this->wrappedObject->user_id == null)
		{
			$user = new UserModel();
			if($this->wrappedObject->username != null)
			{
				$user->name = $this->wrappedObject->username;
			} else
			{
				$user->name = trans('general.guest');
			}

			$decoratedUser = app()->make('MyBB\Core\Presenters\User', [$user]);

			return $decoratedUser;
		}

		return $this->wrappedObject->author;
	}
}
