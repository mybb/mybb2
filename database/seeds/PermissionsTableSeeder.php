<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;
use MyBB\Core\Permissions\PermissionChecker;

class PermissionsTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('permissions')->delete();

		$permissions = [
			[
				'permission_name' => 'canEnterACP',
				'content_name'    => null,
				'default_value'   => PermissionChecker::NO
			],
			[
				'permission_name' => 'canEnterMCP',
				'content_name'    => null,
				'default_value'   => PermissionChecker::NO
			],
			[
				'permission_name' => 'canEnterUCP',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canViewForum',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canUseConversations',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canViewAllOnline',
				'content_name'    => null,
				'default_value'   => PermissionChecker::NO
			],
			[
				'permission_name' => 'canModerate',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canApprove',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canClose',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canDeletePosts',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canDeleteTopics',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canMergePosts',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canMovePosts',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canMoveTopics',
				'content_name' => null,
				'default_value' => 0
			],
			[
				'permission_name' => 'canPostTopics',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canReply',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canAddPolls',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canEditPolls',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::NO
			],
			[
				'permission_name' => 'canEditOwnPolls',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canVoteInPolls',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canOnlyViewOwnTopics',
				'content_name'    => 'forum',
				'default_value'   => PermissionChecker::NO
			],
			[
				'permission_name' => 'canViewProfiles',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canUseCustomTitle',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canUploadAvatar',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canViewWhosOnline',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
			[
				'permission_name' => 'canViewMemberlist',
				'content_name'    => null,
				'default_value'   => PermissionChecker::YES
			],
		];

		DB::table('permissions')->insert($permissions);
	}
}
