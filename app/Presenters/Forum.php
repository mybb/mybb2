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
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\Forum as ForumModel;
use MyBB\Core\Database\Models\User as UserModel;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Core\Database\Models\Topic as TopicModel;
use MyBB\Core\Moderation\ModerationRegistry;

class Forum extends BasePresenter
{
	/**
	 * @var ModerationRegistry
	 */
	protected $moderations;

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var PermissionChecker
	 */
	private $permissionChecker;

	/**
	 * @var Guard
	 */
	private $guard;

	/**
	 * @param ForumModel         $resource          The forum being wrapped by this presenter.
	 * @param Application        $app
	 * @param PermissionChecker  $permissionChecker
	 * @param Guard              $guard
	 * @param ModerationRegistry $moderations
	 */
	public function __construct(
		ForumModel $resource,
		Application $app,
		PermissionChecker $permissionChecker,
		Guard $guard,
		ModerationRegistry $moderations
	) {
		$this->wrappedObject = $resource;
		$this->app = $app;
		$this->permissionChecker = $permissionChecker;
		$this->guard = $guard;
		$this->moderations = $moderations;
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
		if ($this->lastPost() == null) {
			return false;
		}

		return true;
	}

	/**
	 * @return \MyBB\Core\Moderation\ModerationInterface[]
	 */
	public function moderations()
	{
		$moderations = $this->moderations->getForContent(new TopicModel());
		$decorated = [];
		$presenter = app()->make('autopresenter');

		foreach ($moderations as $moderation) {
			$decorated[] = $presenter->decorate($moderation);
		}

		return $decorated;
	}

	/**
	 * @return Post|null
	 */
	public function lastPost()
	{
		if (!$this->permissionChecker->hasPermission('forum', $this->wrappedObject->id, 'canOnlyViewOwnTopics')) {
			return $this->wrappedObject->lastPost;
		}

		if ($this->wrappedObject->lastPost->topic->user_id == $this->guard->user()->id) {
			return $this->wrappedObject->lastPost;
		}

		return null;
	}

	/**
	 * @param string $permission
	 * @param User   $user
	 *
	 * @return bool
	 */
	public function hasPermission($permission, $user = null)
	{
		return $this->permissionChecker->hasPermission('forum', $this->wrappedObject->id, $permission, $user);
	}
}
