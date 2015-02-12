<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        $users = [
            [
            	'name' 			=> 'Admin',
            	'email' 		=> 'admin@mybb.com',
            	'password'		=> Hash::make('password'),
                'role_id'       => 1,
            	'created_at' 	=> new DateTime,
            	'updated_at' 	=> new DateTime
            ],
            [
            	'name' 			=> 'Registered',
            	'email' 		=> 'user@mybb.com',
            	'password'		=> Hash::make('password'),
                'role_id'       => 2,
            	'created_at' 	=> new DateTime,
            	'updated_at' 	=> new DateTime
            ],
            [
            	'name' 			=> 'Banned',
            	'email' 		=> 'fake@mybb.com',
            	'password'		=> Hash::make('password'),
                'role_id'       => 3,
            	'created_at' 	=> new DateTime,
            	'updated_at' 	=> new DateTime
            ],
        ];

       DB::table('users')->insert($users);
    }

}