<?php

namespace MyBB\Core\Permissions\Traits;

use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Services\PermissionChecker;

trait Permissionable
{
	/**
	 * @return int
	 */
	public function getContentId()
	{
		return $this->getKey();
	}

	/**
	 * @return string
	 */
	public static function getViewablePermission()
	{
		return 'canView' . ucfirst(class_basename(__CLASS__));
	}
}
