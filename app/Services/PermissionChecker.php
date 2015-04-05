<?php

namespace MyBB\Core\Services;

use Illuminate\Database\DatabaseManager;
use MyBB\Core\Database\Models\ContentClass;
use MyBB\Core\Database\Models\Role;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class PermissionChecker
{
	const NEVER = -1;
	const NO = 0;
	const YES = 1;

	/** @var CacheRepository $cache */
	private $cache;

	/** @var DatabaseManager $db */
	private $db;

	/** @var ContentClass $classModel */
	private $classModel;

	/**
	 * @param CacheRepository $cache
	 * @param DatabaseManager $db
	 * @param ContentClass    $classModel
	 */
	public function __construct(CacheRepository $cache, DatabaseManager $db, ContentClass $classModel)
	{
		$this->cache = $cache;
		$this->db = $db;
		$this->classModel = $classModel;
	}

	/**
	 * Get an array of unviewable ids for the registered content type
	 *
	 * @param string $content
	 *
	 * @return array
	 */
	public function getUnviewableIdsForContent($content)
	{
		$concreteClass = $this->classModel->getClass($content);

		if ($concreteClass == null) {
			throw new \RuntimeException("No class is registered for content type '{$content}'");
		}

		return $concreteClass->getUnviewableIds();
	}

	/**
	 * Check whether a specific Role has the specified permission
	 *
	 * @param Role        $role       The role to check
	 * @param string      $permission The permission to check
	 * @param string|null $content    If the permission is related to some content (eg forum) this string specifies the
	 *                                type of text
	 * @param int|null    $contentID  If $content is set this specifies the ID of the content to check
	 *
	 * @return PermissionChecker::NEVER|NO|YES
	 */
	public function hasPermission(Role $role, $permission, $content = null, $contentID = null)
	{
		// Permissions associated with user/groups are saved without content (all permissions are associated with groups anyways)
		if ($content == 'user' || $content == 'usergroup') {
			$content = null;
		}

		//if ($this->hasCache($role, $permission, $content, $contentID)) {
		//	return $this->getCache($role, $permission, $content, $contentID);
		//}

		// Get the value if we have one otherwise the devault value
		$permission = $this->db->table('permissions')
			->where('permission_name', '=', $permission)
			->where('content_name', '=', $content)
			->leftJoin('permission_role', function ($join) use ($role, $content, $contentID) {
				$join->on('permission_id', '=', 'id')
					->where('role_id', '=', $role->id);

				if ($content != null && $contentID != null) {
					$join->where('content_id', '=', $contentID);
				}
			})
			->first(['value', 'default_value']);

		if ($permission->value !== null) {
			//$this->putCache($role, $permission, $content, $contentID, $permission->value);

			return $permission->value;
		}

		//$this->putCache($role, $permission, $content, $contentID, $permission->default_value);

		return $permission->default_value;
	}

	/**
	 * @param Role        $role
	 * @param string      $permission
	 * @param string|null $content
	 * @param int|null    $contentID
	 *
	 * @return bool
	 */
	private function hasCache(Role $role, $permission, $content, $contentID)
	{
		return $this->getCache($role, $permission, $content, $contentID) != null;
	}

	/**
	 * @param Role        $role
	 * @param string      $permission
	 * @param string|null $content
	 * @param int|null    $contentID
	 *
	 * @return mixed
	 */
	private function getCache(Role $role, $permission, $content, $contentID)
	{
		return $this->cache->get("permission.{$role->slug}.{$permission}.{$content}.{$contentID}");
	}

	/**
	 * @param Role         $role
	 * @param string       $permission
	 * @param string|null  $content
	 * @param int|null     $contentID
	 * @param NEVER|NO|YES $value
	 */
	private function putCache(Role $role, $permission, $content, $contentID, $value)
	{
		$this->cache->forever("permission.{$role->slug}.{$permission}.{$content}.{$contentID}", $value);
	}
}
