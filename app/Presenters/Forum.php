<?php
/**
 * Forum presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Foundation\Application;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Forum as ForumModel;
use MyBB\Core\Database\Models\User as UserModel;

class Forum extends BasePresenter
{
	/** @var ForumModel $wrappedObject */

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @param ForumModel  $resource The forum being wrapped by this presenter.
	 * @param Application $app
	 */
	public function __construct(ForumModel $resource, Application $app)
	{
		$this->wrappedObject = $resource;
		$this->app = $app;
	}

	/**
	 * @return User
	 */
	public function lastPostAuthor()
	{
		if ($this->wrappedObject->last_post_user_id == null) {
			$user = new UserModel();
			$user->id = 0;
			if ($this->wrappedObject->lastPost->username != null) {
				$user->name = $this->wrappedObject->lastPost->username;
			} else {
				$user->name = trans('general.guest');
			}

			$decoratedUser = $this->app->make('MyBB\Core\Presenters\User', [$user]);

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
