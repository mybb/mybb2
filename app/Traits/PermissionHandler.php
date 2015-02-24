<?php namespace MyBB\Core\Traits;


trait PermissionHandler
{


	public function canAccess($requiredPermission = false)
	{
		if ($requiredPermission) {
			return $this->checkPermission($requiredPermission);
		}

		return true;
	}

	public function checkPermission($requiredPermission)
	{
		$permissions = $this->role->permissions->fetch('permission_slug');

		$permissions = array_map('strtolower', $permissions->toArray());

		return count(array_intersect($permissions, $requiredPermission));

	}

}
