<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('permissions')->delete();

        $permissions = [
            [
                'permission_name' => 'canEnterACP',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canEnterMCP',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canEnterUCP',
                'content_name'    => null,
                'default_value'   => 1,
            ],
            [
                'permission_name' => 'canViewForum',
                'content_name'    => 'forum',
                'default_value'   => 1,
            ],
            [
                'permission_name' => 'canUseConversations',
                'content_name'    => null,
                'default_value'   => 1,
            ],
            [
                'permission_name' => 'canViewAllOnline',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canModerate',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canApprove',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canClose',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canStick',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canDeletePosts',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canDeleteTopics',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canMergePosts',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canMovePosts',
                'content_name'    => null,
                'default_value'   => 0,
            ],
            [
                'permission_name' => 'canMoveTopics',
                'content_name'    => null,
                'default_value'   => 0,
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
