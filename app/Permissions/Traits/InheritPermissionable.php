<?php namespace MyBB\Core\Permissions\Traits;

use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Services\PermissionChecker;

trait InheritPermissionable
{
	use Permissionable;

	/**
	 * @return InheritPermissionable
	 */
    public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Returns an array of permissions where a positive permission in one of the parents overrides negative permissions
	 * in its child
	 *
	 * @return array
	 */
    public static function getPositiveParentOverrides()
	{
		return [];
	}

	/**
	 * Returns an array of permissions where a negative permission in one of the parents overrides positive permissions
	 * in its child By default the viewable permission is returned
	 *
	 * @return array
	 */
	public static function getNegativeParentOverrides()
	{
		return [
			static::getViewablePermission()
		];
	}


}
