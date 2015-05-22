<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Permissions;

use Illuminate\Database\DatabaseManager;
use MyBB\Core\Database\Models\ContentClass;
use MyBB\Core\Database\Models\Role;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Exceptions\PermissionImplementInterfaceException;
use MyBB\Core\Exceptions\PermissionInvalidContentException;
use MyBB\Core\Permissions\Interfaces\InheritPermissionInterface;
use MyBB\Core\Permissions\Interfaces\PermissionInterface;

class PermissionChecker
{
	// Constants used as permission value
	const NEVER = -1;
	const NO = 0;
	const YES = 1;

	/**
	 * @var CacheRepository
	 */
	private $cache;

	/**
	 * @var DatabaseManager
	 */
	private $db;

	/**
	 * @var ContentClass
	 */
	private $classModel;

	/**
	 * @var array
	 */
	private $permissions;

	/**
	 * @var array
	 */
	private $unviewableIds;

	/**
	 * @var Role
	 */
	private $guestRole;

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
	 * @param User   $user
	 *
	 * @return array
	 *
	 * @throws PermissionInvalidContentException
	 * @throws PermissionImplementInterfaceException
	 */
	public function getUnviewableIdsForContent($content, User $user = null)
	{
		$concreteClass = $this->classModel->getClass($content);

		if ($concreteClass == null) {
			throw new PermissionInvalidContentException($content);
		}

		if (!($concreteClass instanceof PermissionInterface)) {
			throw new PermissionImplementInterfaceException($content);
		}

		if ($this->unviewableIds[$content] != null) {
			return $this->unviewableIds[$content];
		}

		$models = $concreteClass::all();

		$unviewable = [];
		foreach ($models as $model) {
			if (!$this->hasPermission($content, $model->getContentId(), $concreteClass::getViewablePermission(), $user)
			) {
				$unviewable[] = $model->getContentId();
			}
		}

		$this->unviewableIds[$content] = $unviewable;

		return $unviewable;
	}


	/**
	 * Checks whether the specified user has the specified permission
	 *
	 * @param string       $content
	 * @param int          $contentID
	 * @param array|string $permission
	 * @param User         $user
	 *
	 * @return bool
	 *
	 * @throws PermissionInvalidContentException
	 * @throws PermissionImplementInterfaceException
	 */
	public function hasPermission($content, $contentID, $permission, User $user = null)
	{
		$concreteClass = $this->classModel->getClass($content);

		if ($concreteClass == null) {
			throw new PermissionInvalidContentException($content);
		}

		if (!($concreteClass instanceof PermissionInterface)) {
			throw new PermissionImplementInterfaceException($content);
		}

		if ($user == null) {
			$user = app('auth.driver')->user();
		}

		// Handle the array case
		if (is_array($permission)) {
			foreach ($permission as $perm) {
				$hasPermission = $this->hasPermission($content, $contentID, $perm, $user);

				// No need to check more permissions
				if (!$hasPermission) {
					return false;
				}
			}

			return true;
		}

		// We already calculated the permissions for this user, no need to recheck all roles
		if (isset($this->permissions[$content][$contentID][$user->getKey()][$permission])) {
			return $this->permissions[$content][$contentID][$user->getKey()][$permission];
		}

		// Handle special cases where no role has been set
		$roles = $user->roles;
		if ($roles->count() == 0) {
			if ($user->exists) {
				// User saved? Something is wrong, attach the registered role
				$registeredRole = Role::whereSlug('user');
				$user->roles()->attach($registeredRole->id, ['is_display' => 1]);
				$roles = [$registeredRole];
			} else {
				// Guest
				if ($this->guestRole == null) {
					$this->guestRole = Role::whereSlug('guest');
				}
				$roles = [$this->guestRole];
			}
		}

		// Assume "No" by default
		$isAllowed = false;
		foreach ($roles as $role) {
			$hasPermission = $this->getPermissionForRole($role, $permission, $content, $contentID);

			// If we never want to grant the permission we can skip all other roles. But don't forget to cache it
			if ($hasPermission == PermissionChecker::NEVER) {
				$isAllowed = false;
				break;
			} // Override our "No" assumption - but don't return yet, we may have a "Never" permission later
			elseif ($hasPermission == PermissionChecker::YES) {
				$isAllowed = true;
			}
		}

		// No parent? No need to do anything else here
		if (($concreteClass instanceof InheritPermissionInterface)
			&& $concreteClass::find($contentID)->getParent() != null
		) {
			// If we have a positive permission but need to check parents for negative values do so here
			if ($isAllowed && in_array($permission, $concreteClass::getNegativeParentOverrides())) {
				$isAllowed = $this->hasPermission(
					$content,
					$concreteClass::find($contentID)->getParent()->getContentId(),
					$permission,
					$user
				);
			}

			// Do the same for negative permissions with parent positives
			if (!$isAllowed && in_array($permission, $concreteClass::getPositiveParentOverrides())) {
				$isAllowed = $this->hasPermission(
					$content,
					$concreteClass::find($contentID)->getParent()->getContentId(),
					$permission,
					$user
				);
			}
		}

		// Don't forget to cache the permission for this call
		$this->permissions[$content][$contentID][$user->getKey()][$permission] = $isAllowed;

		return $isAllowed;
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
	public function getPermissionForRole(Role $role, $permission, $content = null, $contentID = null)
	{
		// Permissions associated with user/groups are saved without content
		// (all permissions are associated with groups anyways)
		if ($content == 'user' || $content == 'usergroup') {
			$content = null;
			$contentID = null;
		}

		if ($this->hasCache($role, $permission, $content, $contentID)) {
			return $this->getCache($role, $permission, $content, $contentID);
		}

		// Get the value if we have one otherwise the devault value
		$permissionValues = $this->db->table('permissions')
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

		// If the permission doesn't exist return "Never" to break all loops but don't cache it as it may be added later
		if ($permissionValues == null) {
			return static::NEVER;
		}

		if ($permissionValues->value !== null) {
			$this->putCache($role, $permission, $content, $contentID, $permissionValues->value);

			return $permissionValues->value;
		}

		$this->putCache($role, $permission, $content, $contentID, $permissionValues->default_value);

		return $permissionValues->default_value;
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
		return $this->getCache($role, $permission, $content, $contentID) !== null;
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
		return $this->cache->get("permission.{$role->role_slug}.{$permission}.{$content}.{$contentID}");
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
		$this->cache->forever("permission.{$role->role_slug}.{$permission}.{$content}.{$contentID}", $value);
	}
}
