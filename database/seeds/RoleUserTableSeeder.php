<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('role_user')->delete();

        $role_user = [
            [
                'user_id'    => DB::table('users')->where('name', 'Admin')->first()->id,
                'role_id'    => DB::table('roles')->where('role_slug', 'admin')->first()->id,
                'is_display' => true,
            ],
            [
                'user_id'    => DB::table('users')->where('name', 'Registered')->first()->id,
                'role_id'    => DB::table('roles')->where('role_slug', 'user')->first()->id,
                'is_display' => true,
            ],
            [
                'user_id'    => DB::table('users')->where('name', 'Banned')->first()->id,
                'role_id'    => DB::table('roles')->where('role_slug', 'banned')->first()->id,
                'is_display' => true,
            ],
        ];

        DB::table('role_user')->insert($role_user);
    }
}
