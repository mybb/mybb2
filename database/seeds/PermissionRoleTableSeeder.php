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
				'value'         => PermissionChecker::YES,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canPostTopics'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canPostTopics'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canReply'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canReply'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canAddPolls'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canAddPolls'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canEditPolls'),
				'role_id'       => $this->role('admin'),
				'value'         => PermissionChecker::YES,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canEditOwnPolls'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canEditOwnPolls'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canVoteInPolls'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canVoteInPolls'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => 0
			],
			[
				'permission_id' => $this->perm('canViewProfiles'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NEVER,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canUseCustomTitle'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canUseCustomTitle'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canUploadAvatar'),
				'role_id'       => $this->role('guest'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canUploadAvatar'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => $this->perm('canViewMemberlist'),
				'role_id'       => $this->role('banned'),
				'value'         => PermissionChecker::NO,
				'content_id'    => null
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canModerate')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canApprove')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canClose')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canDeletePosts')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canDeleteTopics')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canMergePosts')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canMovePosts')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
			],
			[
				'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canMoveTopics')
					->pluck('id'),
				'role_id' => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'value' => 1
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
