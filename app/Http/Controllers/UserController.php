<?php

namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Exceptions\UserNotFoundException;

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

	/**
	 * @param UserRepositoryInterface             $users
	 * @param UserProfileFieldRepositoryInterface $userProfileFields
	 */
	public function __construct(
		UserRepositoryInterface $users,
		UserProfileFieldRepositoryInterface $userProfileFields
	) {
		$this->users = $users;
		$this->userProfileFields = $userProfileFields;
	}

	/**
	 * @param string                               $slug
	 * @param int                                  $id
	 * @param ProfileFieldGroupRepositoryInterface $profileFieldGroups
	 *
	 * @return \Illuminate\View\View
	 */
	public function profile($slug, $id, ProfileFieldGroupRepositoryInterface $profileFieldGroups)
	{
		$user = $this->users->find($id);

		if (!$user) {
			throw new UserNotFoundException;
		}

		$groups = $profileFieldGroups->getAll();

		return view('user.profile', [
			'user' => $user,
			'profile_field_groups' => $groups
		]);
	}
}
