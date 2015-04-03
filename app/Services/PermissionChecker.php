<?php

namespace MyBB\Core\Services;

use \DB;
use MyBB\Core\Database\Models\Role;

class PermissionChecker
{
	const NEVER = -1;
	const NO = 0;
	const YES = 1;

	// TODO: THIS NEEDS TO BE CACHED!
	public static function hasPermission(Role $role, $permission, $content = null, $contentID = null)
	{
		if ($content == 'user' || $content == 'usergroup') {
			$content = null;
		}

		$baseQuery = DB::table('permissions')
			->where('permission_name', '=', $permission)
			->where('content_name', '=', $content)
			->leftJoin('permission_role', function ($join) use ($role, $contentID) {
				$join->on('permission_id', '=', 'id')
					->where('role_id', '=', $role->id);

				if ($contentID != null) {
					$join->where('content_id', '=', $contentID);
				}
			});

		$permission = $baseQuery->first(['value', 'default_value']);

		if ($permission->value != null) {
			return $permission->value;
		}

		return $permission->default_value;
	}
}
