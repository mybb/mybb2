<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();

        $users = [
            [
                'name'       => 'Admin',
                'email'      => 'admin@mybb.com',
                'password'   => Hash::make('password'),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
                'last_visit' => new DateTime,
            ],
            [
                'name'       => 'Registered',
                'email'      => 'user@mybb.com',
                'password'   => Hash::make('password'),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
                'last_visit' => new DateTime,
            ],
            [
                'name'       => 'Banned',
                'email'      => 'fake@mybb.com',
                'password'   => Hash::make('password'),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
                'last_visit' => new DateTime,
            ],
        ];

        DB::table('users')->insert($users);
    }
}
