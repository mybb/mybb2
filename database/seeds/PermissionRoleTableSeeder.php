<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;
use MyBB\Core\Permissions\PermissionChecker;

class PermissionRoleTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('permission_role')->delete();

		$permissions_role = [
			[
				'permission_id' => $this->perm('canEnterACP'),
				'role_id'       => $this->role('admin'),
				'value'         => PermissionChecker::YES,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canEnterUCP'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canEnterUCP'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NEVER,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canUseConversations'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canUseConversations'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NEVER,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canViewAllOnline'),
				'role_id'       => $this->role('admin'),
				'content_id'    => null,
				'value'         => PermissionChecker::YES
			],
			[
				'permission_id' => $this->perm('canPostTopic'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canPostTopic'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
		];

		DB::table('permission_role')->insert($permissions_role);
	}

	/**
	 * @param string $permission
	 *
	 * @return int
	 */
	private function perm($permission)
	{
		return DB::table('permissions')->where('permission_name', '=', $permission)->pluck('id');
	}

	/**
	 * @param string $role
	 *
	 * @return int
	 */
	private function role($role)
	{
		return DB::table('roles')->where('role_slug', '=', $role)->pluck('id');
	}
}
