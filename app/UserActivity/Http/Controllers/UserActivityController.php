<?php
/**
 * User activity controller.
 *
 * Shows recent user activity.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Http\Controllers;

use MyBB\Core\Http\Controllers\AbstractController;
use MyBB\Core\UserActivity\Database\Repositories\UserActivityRepositoryInterface;
use MyBB\Settings\Store;

class UserActivityController extends AbstractController
{
	/**
	 * @var UserActivityRepositoryInterface $userActivityRepository
	 */
	private $userActivityRepository;
	/**
	 * @var Store $settings
	 */
	private $settings;

	/**
	 * @param UserActivityRepositoryInterface $userActivityRepository
	 * @param Store                           $settings
	 */
	public function __construct(
		UserActivityRepositoryInterface $userActivityRepository,
		Store $settings
	) {
		$this->userActivityRepository = $userActivityRepository;
		$this->settings = $settings;
	}

	/**
	 * Get the index list showing all user activity.
	 *
	 * @return \Illuminate\View\View
	 */
	public function getIndex()
	{
		$perPage = $this->settings->get('user_activity.per_page', 20);

		/** @var \Illuminate\Pagination\Paginator $activities */
		$activities = $this->userActivityRepository->paginateAll($perPage);

		return view('user_activity.index', compact('activities'));
	}
}
