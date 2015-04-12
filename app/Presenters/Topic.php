<?php
/**
 * Thread presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Foundation\Application;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Models\Topic as TopicModel;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Moderation\ModerationRegistry;

class Topic extends BasePresenter
{
	/** @var TopicModel $wrappedObject */

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var ModerationRegistry
	 */
	protected $moderations;

	/**
	 * @param TopicModel $resource The thread being wrapped by this presenter.
	 * @param ModerationRegistry $moderations
	 * @param Application $app
	 */
	public function __construct(TopicModel $resource, ModerationRegistry $moderations, Application $app)
	{
		$this->wrappedObject = $resource;
		$this->moderations = $moderations;
		$this->app = $app;
	}

	/**
	 * @return int
	 */
	public function replies()
	{
		return $this->wrappedObject->num_posts - 1;
	}

	/**
	 * @return User
	 */
	public function author()
	{
		if ($this->wrappedObject->user_id == null) {
			$user = new UserModel();
			if ($this->wrappedObject->username != null) {
				$user->name = $this->wrappedObject->username;
			} else {
				$user->name = trans('general.guest');
			}

			$decoratedUser = $this->app->make('MyBB\Core\Presenters\User', [$user]);

			return $decoratedUser;
		}

		return $this->wrappedObject->author;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function moderations()
	{
		return $this->moderations->getForContent(new Post());
	}
}
