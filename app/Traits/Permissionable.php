<?php namespace MyBB\Core\Traits;

use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Services\PermissionChecker;

trait Permissionable
{

	private static function getContentName()
	{
		return null;
	}

	private function getContentId()
	{
		return $this->getKey();
	}

	private static function getViewablePermission()
	{
		return 'canView'.ucfirst(static::getContentName());
	}

	public static function getUnviewableIds(User $user = null)
	{
		$models = static::all();

		$unviewable = [];
		foreach($models as $model)
		{
			if(!$model->hasPermission(static::getViewablePermission(), $user))
			{
				$unviewable[] = $model->getKey();
			}
		}

		return $unviewable;
	}

	public function hasPermission($permission, User $user = null)
	{
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

		// TODO: Cache this foreach?
		$isAllowed = false;
		foreach ($roles as $role) {
			$hasPermission = PermissionChecker::hasPermission($role, $permission, static::getContentName(),
				$this->getContentId());

			if ($hasPermission == PermissionChecker::NEVER) {
				return false;
			} elseif ($hasPermission == PermissionChecker::YES) {
				$isAllowed = true;
			}
		}

		return $isAllowed;
	}

}
