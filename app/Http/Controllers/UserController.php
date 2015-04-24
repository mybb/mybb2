<?php

namespace MyBB\Core\Http\Controllers;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\UserActivity\Database\Repositories\UserActivityRepositoryInterface;
use MyBB\Settings\Store;

class UserController extends AbstractController
{
	/**
	 * @var UserRepositoryInterface
	 */
	protected $users;

	/**
	 * @var UserProfileFieldRepositoryInterface
	 */
	protected $userProfileFields;

	/** @var UserActivityRepositoryInterface $activityRepository */
	protected $activityRepository;

	/** @var Store $settings */
	protected $settings;

	/**
	 * @param UserRepositoryInterface             $users
	 * @param UserProfileFieldRepositoryInterface $userProfileFields
	 * @param UserActivityRepositoryInterface     $activityRepository
	 */
	public function __construct(
		UserRepositoryInterface $users,
		UserProfileFieldRepositoryInterface $userProfileFields,
		UserActivityRepositoryInterface $activityRepository,
		Store $settings
	) {
		$this->users = $users;
		$this->userProfileFields = $userProfileFields;
		$this->activityRepository = $activityRepository;
		$this->settings = $settings;
	}

	/**
	 * @param string                               $slug
	 * @param int                                  $id
	 * @param ProfileFieldGroupRepositoryInterface $profileFieldGroups
	 * @param Breadcrumbs                          $breadcrumbs
	 *
	 * @return \Illuminate\View\View
	 */
	public function profile(
		$slug,
		$id,
		ProfileFieldGroupRepositoryInterface $profileFieldGroups,
		Breadcrumbs $breadcrumbs
	) {
		$user = $this->users->find($id);
		$groups = $profileFieldGroups->getAll();
		$activity = $this->activityRepository->paginateForUser(
			$user,
			$this->settings->get(
				'user_profile.activity_per_page',
				20
			)
		);

		$breadcrumbs->setCurrentRoute('user.profile', $user);

		return view(
			'user.profile',
			[
				'user'                 => $user,
				'profile_field_groups' => $groups,
				'activity'             => $activity,
			]
		);
	}
}
