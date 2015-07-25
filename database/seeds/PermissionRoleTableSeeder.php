<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('permission_role')->delete();

		$permissions_role = [
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterACP')->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterUCP')->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'banned')->pluck('id'),
				'value' => 0
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterUCP')->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'guest')->pluck('id'),
				'value' => -1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canUseConversations')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'banned')->pluck('id'),
				'value' => 0
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canUseConversations')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'guest')->pluck('id'),
				'value' => -1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canViewAllOnline')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canModerateInline')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
		];

		DB::table('permission_role')->insert($permissions_role);
	}
}
