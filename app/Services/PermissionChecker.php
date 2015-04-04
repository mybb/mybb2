<?php

namespace MyBB\Core\Services;

use Illuminate\Database\DatabaseManager;
use MyBB\Core\Database\Models\Role;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class PermissionChecker
{
	const NEVER = -1;
	const NO = 0;
	const YES = 1;

	private $cache;
	private $db;

	public function __construct(CacheRepository $cache, DatabaseManager $db)
	{
		$this->cache = $cache;
		$this->db = $db;
	}


	public function hasPermission(Role $role, $permission, $content = null, $contentID = null)
	{
		if ($content == 'user' || $content == 'usergroup') {
			$content = null;
		}

		//if ($this->hasCache($role, $permission, $content, $contentID)) {
		//	return $this->getCache($role, $permission, $content, $contentID);
		//}

		$baseQuery = $this->db->table('permissions')
			->where('permission_name', '=', $permission)
			->where('content_name', '=', $content)
			->leftJoin('permission_role', function ($join) use ($role, $content, $contentID) {
				$join->on('permission_id', '=', 'id')
					->where('role_id', '=', $role->id);

				if ($content != null && $contentID != null) {
					$join->where('content_id', '=', $contentID);
				}
			});

		$permission = $baseQuery->first(['value', 'default_value']);

		if ($permission->value !== null) {
			//$this->putCache($role, $permission, $content, $contentID, $permission->value);

			return $permission->value;
		}

		//$this->putCache($role, $permission, $content, $contentID, $permission->default_value);

		return $permission->default_value;
	}

	private function hasCache(Role $role, $permission, $content, $contentID)
	{
		return $this->getCache($role, $permission, $content, $contentID) != null;
	}

	private function getCache(Role $role, $permission, $content, $contentID)
	{
		return $this->cache->get("permission.{$role->slug}.{$permission}.{$content}.{$contentID}");
	}

	private function putCache(Role $role, $permission, $content, $contentID, $value)
	{
		$this->cache->forever("permission.{$role->slug}.{$permission}.{$content}.{$contentID}", $value);
	}
}
