<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Models\ProfileFieldGroup;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\UserProfileField;

interface UserProfileFieldRepositoryInterface
{
	/**
	 * @param User         $user
	 * @param ProfileField $profileField
	 * @param string       $value
	 *
	 * @return UserProfileField
	 */
	public function create(User $user, ProfileField $profileField, $value);

	/**
	 * @param User         $user
	 * @param ProfileField $profileField
	 * @param string       $value
	 *
	 * @return UserProfileField
	 */
	public function updateOrCreate(User $user, ProfileField $profileField, $value);

	/**
	 * @param int $id
	 *
	 * @return UserProfileField
	 */
	public function find($id);

	/**
	 * @param User $user
	 *
	 * @return Collection
	 */
	public function findForUser(User $user);

	/**
	 * @param User         $user
	 * @param ProfileField $profileField
	 *
	 * @return UserProfileField
	 */
	public function findForProfileField(User $user, ProfileField $profileField);

	/**
	 * @param User              $user
	 * @param ProfileFieldGroup $group
	 *
	 * @return Collection
	 */
	public function findForProfileFieldGroup(User $user, ProfileFieldGroup $group);

	/**
	 * @param User         $user
	 * @param ProfileField $profileField
	 *
	 * @return bool
	 */
	public function hasForProfileField(User $user, ProfileField $profileField);
}
