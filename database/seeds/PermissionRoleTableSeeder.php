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
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterACP')->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterMCP')->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterUCP')->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'banned')->first()->id,
                'value'         => 0,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canEnterUCP')->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'guest')->first()->id,
                'value'         => -1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canUseConversations')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'banned')->first()->id,
                'value'         => 0,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canUseConversations')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'guest')->first()->id,
                'value'         => -1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canViewAllOnline')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canModerate')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canApprove')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canClose')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canDeletePosts')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canDeleteTopics')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canMergePosts')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canMovePosts')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
            [
                'permission_id' => DB::table('permissions')->where('permission_name', '=', 'canMoveTopics')
                    ->first()->id,
                'role_id'       => DB::table('roles')->where('role_slug', '=', 'admin')->first()->id,
                'value'         => 1,
            ],
        ];

        DB::table('permission_role')->insert($permissions_role);
    }
}
