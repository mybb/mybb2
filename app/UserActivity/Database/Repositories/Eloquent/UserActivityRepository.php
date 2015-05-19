<?php
/**
 * User Activity repository using the Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\User;
use MyBB\Core\UserActivity\Contracts\ActivityStoreableInterface;
use MyBB\Core\UserActivity\Database\Models\UserActivity;
use MyBB\Core\UserActivity\Database\Repositories\UserActivityRepositoryInterface;

class UserActivityRepository implements UserActivityRepositoryInterface
{
	/**
	 * @var UserActivity $userActivityModel
	 */
	private $userActivityModel;

	/**
	 * @param UserActivity $userActivityModel
	 */
	public function __construct(UserActivity $userActivityModel)
	{
		$this->userActivityModel = $userActivityModel;
	}

	/**
	 * Get all user activity entries.
	 *
	 * @return mixed
	 */
	public function all()
	{
		return $this->userActivityModel->with(['user'])->orderBy('created_at', 'desc')->all();
	}

	/**
	 * Get all user activity entries for a specific user.
	 *
	 * @param int|User $user The user to retrieve activity entries for.
	 *
	 * @return mixed
	 */
	public function allForUser($user)
	{
		$user = $this->getUserIdFromUser($user);

		return $this->userActivityModel->with(['user'])
									   ->where('user_id', '=', $user)
									   ->orderBy('created_at', 'desc')
									   ->get();
	}

	/**
	 * Delete all activity entries for a user where the creation date is older than a given time-span.
	 *
	 * @param int|User      $user     The user to delete activity entries for.
	 * @param \DateInterval $timeSpan The maximum age of user activity entries to keep.
	 *
	 * @return int The number of deleted user activity entries.
	 */
	public function deleteForUserOlderThan($user, \DateInterval $timeSpan)
	{
		$before = $this->getDateTimeFromInterval($timeSpan);

		$user = $this->getUserIdFromUser($user);

		if ($before !== false) {
			return $this->userActivityModel->where('created_at', '<', $before)->where('user_id', '=', $user)->delete();
		}

		return 0;
	}

	/**
	 * Delete all activity entries for a user.
	 *
	 * @param int|User $user The user to delete activity entries for.
	 *
	 * @return int The number of deleted user activity entries.
	 */
	public function deleteAllForUser($user)
	{
		$user = $this->getUserIdFromUser($user);

		return $this->userActivityModel->where('user_id', '=', $user)->delete();
	}

	/**
	 * Get a paginated list of all user activity entries.
	 *
	 * @param int $perPage The number of activity entries per page.
	 *
	 * @return mixed
	 */
	public function paginateAll($perPage = 20)
	{
		return $this->userActivityModel->with(['user'])->orderBy('created_at', 'desc')->paginate($perPage);
	}

	/**
	 * Get a paginated list of activity entries for a specific user.
	 *
	 * @param int|User $user    The user to retrieve activity entries for.
	 * @param int      $perPage The number of activity entries per page.
	 *
	 * @return mixed
	 */
	public function paginateForUser($user, $perPage = 20)
	{
		$user = $this->getUserIdFromUser($user);

		return $this->userActivityModel->with(['user'])
									   ->where('user_id', '=', $user)
									   ->orderBy('created_at', 'desc')
									   ->paginate($perPage);
	}

	/**
	 * Create an activity entry for the given content and user.
	 *
	 * @param ActivityStoreableInterface $content The content to store
	 * @param int                        $userId  The ID of the current user.
	 *
	 * @return UserActivity
	 */
	public function createForContentAndUser(ActivityStoreableInterface $content, $userId)
	{
		return $this->userActivityModel->create(
			[
				'user_id'       => (int) $userId,
				'activity_id'   => $content->getContentId(),
				'activity_type' => get_class($content),
				'extra_details' => $content->getExtraDetails(),
			]
		);
	}

	/**
	 * Get the ID of a user from either an integer or User model.
	 *
	 * @param int|User $user The user to retrieve the User ID for.
	 *
	 * @return int
	 */
	private function getUserIdFromUser($user)
	{
		if (is_object($user) && $user instanceof User) {
			$user = $user->getAuthIdentifier();
		}

		return (int) $user;
	}

	/**
	 * Get a date representation of a date time interval.
	 *
	 * @param \DateInterval $interval The interval to subtract or add.
	 *
	 * @return \DateTime|false The calculated Date or false upon failure.
	 */
	private function getDateTimeFromInterval(\DateInterval $interval)
	{
		$now = new \DateTime();

		return $now->sub($interval);
	}
}
