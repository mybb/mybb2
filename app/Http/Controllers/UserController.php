<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Exceptions\UserNotFoundException;
use MyBB\Core\Permissions\PermissionChecker;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
	 * @param Breadcrumbs                          $breadcrumbs
	 * @param PermissionChecker                    $permissionChecker
	 *
	 * @return \Illuminate\View\View
	 */
	public function profile(
		$slug,
		$id,
		ProfileFieldGroupRepositoryInterface $profileFieldGroups,
		Breadcrumbs $breadcrumbs,
		PermissionChecker $permissionChecker
	) {
		$user = $this->users->find($id);

		if (!$user) {
			throw new UserNotFoundException;
		}

		$breadcrumbs->setCurrentRoute('user.profile', $user);

		if (!$permissionChecker->hasPermission('user', null, 'canViewProfiles')) {
			throw new AccessDeniedHttpException;
		}

		$groups = $profileFieldGroups->getAll();

		return view('user.profile', [
			'user' => $user,
			'profile_field_groups' => $groups
		]);
	}
}
