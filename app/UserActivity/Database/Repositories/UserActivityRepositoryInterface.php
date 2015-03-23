<?php
/**
 * User Activity repository contract.
 *
 * Used to retrieve user activity entries from a data store.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Database\Repositories;

use MyBB\Core\Database\Models\User;

interface UserActivityRepositoryInterface
{
    /**
     * Get all user activity entries.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get a paginated list of all user activity entries.
     *
     * @param int $perPage The number of activity entries per page.
     *
     * @return mixed
     */
    public function paginateAll($perPage = 20);

    /**
     * Get all user activity entries for a specific user.
     *
     * @param int|User $user The user to retrieve activity entries for.
     *
     * @return mixed
     */
    public function allForUser($user);

    /**
     * Get a paginated list of activity entries for a specific user.
     *
     * @param int|User $user    The user to retrieve activity entries for.
     * @param int      $perPage The number of activity entries per page.
     *
     * @return mixed
     */
    public function paginateForUser($user, $perPage = 20);

    /**
     * Delete all activity entries for a user where the creation date is older than a given time-span.
     *
     * @param int|User      $user     The user to delete activity entries for.
     * @param \DateInterval $timeSpan The maximum age of user activity entries to keep.
     *
     * @return int The number of deleted user activity entries.
     */
    public function deleteForUserOlderThan($user, \DateInterval $timeSpan);

    /**
     * Delete all activity entries for a user.
     *
     * @param int|User $user The user to delete activity entries for.
     *
     * @return int The number of deleted user activity entries.
     */
    public function deleteAllForUser($user);
}
