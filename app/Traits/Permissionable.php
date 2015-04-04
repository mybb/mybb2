<?php namespace MyBB\Core\Traits;

use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Services\PermissionChecker;

trait Permissionable
{
	private $permissions;

	private static function getContentName()
	{
		return strtolower(class_basename(__CLASS__));
	}

	private function getContentId()
	{
		return $this->getKey();
	}

	private static function getViewablePermission()
	{
		return 'canView' . ucfirst(static::getContentName());
	}

	public static function getUnviewableIds(User $user = null)
	{
		$models = static::all();

		$unviewable = [];
		foreach ($models as $model) {
			if (!$model->hasPermission(static::getViewablePermission(), $user)) {
				$unviewable[] = $model->getKey();
			}
		}

		return $unviewable;
	}

	public function hasPermission($permission, User $user = null)
	{
		// Special case. Don't allow calling $user->hasPermission('xy', $anotherUser);
		if ($this instanceof User) {
			$user = $this;
		}

		if ($user == null) {
			$user = app('auth.driver')->user();
		}

		if (is_array($permission)) {
			foreach ($permission as $perm) {
				$hasPermission = $this->hasPermission($perm);

				if ($hasPermission != PermissionChecker::YES) {
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

			// If we never want to grant the permission we can skip all other roles. But don't forget to cache it
			if ($hasPermission == PermissionChecker::NEVER) {
				$this->permissions[$user->getKey()][$permission] = false;

				return false;
			} // Override our "No" assumption - but don't return yet, we may have a "Never" permission later
			elseif ($hasPermission == PermissionChecker::YES) {
				$isAllowed = true;
			}
		}

		// Don't forget to cache the permission for this call
		$this->permissions[$user->getKey()][$permission] = $isAllowed;

		return $isAllowed;
	}

}
