<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UsersTableSeeder');
		$this->call('RolesTableSeeder');
		$this->call('PermissionsTableSeeder');
		$this->call('PermissionRoleTableSeeder');

		$this->call('ForumsTableSeeder');

		$this->call('TopicsTableSeeder');
		$this->call('PostsTableSeeder');

		$this->call('UserSettingsTableSeeder');
	}

}
