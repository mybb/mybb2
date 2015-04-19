<?php
/**
 * User activity controller.
 *
 * Shows recent user activity.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Http\Controllers;

use Illuminate\Http\Request;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Controllers\Controller;
use MyBB\Core\UserActivity\Database\Repositories\UserActivityRepositoryInterface;
use MyBB\Settings\Store;

class UserActivityController extends Controller
{
	/** @var Guard $guard */
	private $guard;
	/** @var Request $request */
	private $request;
	/**
	 * @var UserActivityRepositoryInterface $userActivityRepository
	 */
	private $userActivityRepository;
	/**
	 * @var Store $settings
	 */
	private $settings;

	/**
	 * @param Guard                           $guard
	 * @param Request                         $request
	 * @param UserActivityRepositoryInterface $userActivityRepository
	 * @param Store                           $settings
	 */
	public function __construct(
		Guard $guard,
		Request $request,
		UserActivityRepositoryInterface $userActivityRepository,
		Store $settings
	) {
		$this->guard = $guard;
		$this->request = $request;
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
