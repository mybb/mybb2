<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('roles')->delete();

		$roles = [
			[
				'role_display_name' => 'Admin',
				'role_description' => 'The Default Admin Role',
				'role_slug' => 'admin',
				'role_username_style' => '<span style="color: #ff7500;">:user</span>',
				'created_at' => new DateTime,
				'updated_at' => new DateTime
			],
			[
				'role_display_name' => 'User',
				'role_description' => 'A regular User',
				'role_slug' => 'user',
				'role_username_style' => ':user',
				'created_at' => new DateTime,
				'updated_at' => new DateTime
			],
			[
				'role_display_name' => 'Banned',
				'role_description' => 'This Role is banned',
				'role_slug' => 'banned',
				'role_username_style' => '<strike>:user</strike>',
				'created_at' => new DateTime,
				'updated_at' => new DateTime
			],
			[
				'role_display_name' => 'Guest',
				'role_description' => 'The guest group',
				'role_slug' => 'guest',
				'role_username_style' => ':user',
				'created_at' => new DateTime,
				'updated_at' => new DateTime
			],
		];

		DB::table('roles')->insert($roles);
	}
}
