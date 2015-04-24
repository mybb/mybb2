<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('role_user')->delete();

		$role_user = [
			[
				'user_id'    => DB::table('users')->where('name', '=', 'Admin')->pluck('id'),
				'role_id'    => DB::table('roles')->where('role_slug', '=', 'admin')->pluck('id'),
				'is_display' => true
			],
			[
				'user_id'    => DB::table('users')->where('name', '=', 'Registered')->pluck('id'),
				'role_id'    => DB::table('roles')->where('role_slug', '=', 'user')->pluck('id'),
				'is_display' => true
			],
			[
				'user_id'    => DB::table('users')->where('name', '=', 'Banned')->pluck('id'),
				'role_id'    => DB::table('roles')->where('role_slug', '=', 'banned')->pluck('id'),
				'is_display' => true
			],
		];

		DB::table('role_user')->insert($role_user);
	}
}
