<?php namespace MyBB\Core\Traits;

use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Services\PermissionChecker;

trait InheritPermissionable
{
	use Permissionable;

	/**
	 * @return int|null
	 */
	private function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * @return InheritPermissionable
	 */
	private function getParent()
	{
		return $this->parent;
	}

	/**
	 * Returns an array of permissions where a positive permission in one of the parents overrides negative permissions
	 * in its child
	 *
	 * @return array
	 */
	private static function getPositiveParentOverrides()
	{
		return [];
	}

	/**
	 * Returns an array of permissions where a negative permission in one of the parents overrides positive permissions
	 * in its child By default the viewable permission is returned
	 *
	 * @return array
	 */
	private static function getNegativeParentOverrides()
	{
		return [
			static::getViewablePermission()
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasPermission($permission, User $user = null)
	{
		// Special case. Don't allow calling $user->hasPermission('xy', $anotherUser);
		if ($this instanceof User) {
			$user = $this;
		}

		if ($user == null) {
			$user = app('auth.driver')->user();
		}

		// Handle array case
		if (is_array($permission)) {
			foreach ($permission as $perm) {
				$hasPermission = $this->hasPermission($perm);

				if (!$hasPermission) {
					return false;
				}
			}

			return true;
		}

		// We already calculated the permissions for this user, no need to recheck all roles
		if (isset($this->permissions[$user->getKey()][$permission])) {
			return $this->permissions[$user->getKey()][$permission];
		}

		// Handle special cases where no role has been set
		$roles = $user->roles;
		if ($roles->count() == 0) {
			if ($user->exists) {
				// User saved? Something is wrong, attach the registered role
				$registeredRole = Role::where('role_slug', '=', 'user')->first();
				$user->roles()->attach($registeredRole->id, ['is_display' => 1]);
				$roles = [$registeredRole];
			} else {
				// Guest
				$guestRole = Role::where('role_slug', '=', 'guest')->first();
				$roles = [$guestRole];
			}
		}

		$permissionChecker = app()->make('MyBB\\Core\\Services\\PermissionChecker');

		// Assume "No" by default
		$isAllowed = false;
		foreach ($roles as $role) {
			$hasPermission = $permissionChecker->hasPermission($role, $permission, static::getContentName(),
				$this->getContentId());

			// If we never want to grant the permission we can skip all other roles
			// We can't directly return false as we still need to check for parent positives just in case
			if ($hasPermission == PermissionChecker::NEVER) {
				$isAllowed = false;
				break;
			} // Override our "No" assumption - but don't return yet, we may have a "Never" permission later
			elseif ($hasPermission == PermissionChecker::YES) {
				$isAllowed = true;
			}
		}

		// No parent? No need to do anything else here
		if ($this->getParentId() != null) {
			// If we have a positive permission but need to check parents for negative values do so here
			if ($isAllowed && in_array($permission, static::getNegativeParentOverrides())) {
				$isAllowed = $this->getParent()->hasPermission($permission, $user);
			}

			// Do the same for negative permissions with parent positives
			if (!$isAllowed && in_array($permission, static::getPositiveParentOverrides())) {
				$isAllowed = $this->getParent()->hasPermission($permission, $user);
			}
		}

		// Don't forget to cache the permission for this call
		$this->permissions[$user->getKey()][$permission] = $isAllowed;

		return $isAllowed;
	}

}
