<?php
/**
 * Eloquent model observer to listen for 'created' events.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Observers;

use MyBB\Auth\Contracts\Guard;
use MyBB\Core\UserActivity\Contracts\ActivityStoreableInterface;
use MyBB\Core\UserActivity\Database\Repositories\UserActivityRepositoryInterface;

class EloquentObserver
{
	/**
	 * @var Guard $guard
	 */
	private $guard;

	/**
	 * @var UserActivityRepositoryInterface $activityRepository
	 */
	private $activityRepository;

	/**
	 * Create the event handler.
	 *
	 * @param Guard                           $guard
	 * @param UserActivityRepositoryInterface $activityRepository
	 */
	public function __construct(Guard $guard, UserActivityRepositoryInterface $activityRepository)
	{
		$this->guard = $guard;
		$this->activityRepository = $activityRepository;
	}

	/**
	 * Handle a model being created.
	 *
	 * @param ActivityStoreableInterface $model The created model.
	 */
	public function created(ActivityStoreableInterface $model)
	{
		if ($model->checkStoreable()) {
			$userId = null;

			if ($this->guard->check()) {
				$userId = $this->guard->user()->getAuthIdentifier();
			}

			$this->activityRepository->createForContentAndUser(
				$model,
				$userId
			);
		}
	}
}
