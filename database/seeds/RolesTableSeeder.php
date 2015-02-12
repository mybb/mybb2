<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $roles = [
            [
                'role_display_name' => 'Admin',
                'role_description'  => 'The Default Admin Role',
                'role_slug'         => 'admin',
                'created_at'        => new DateTime,
                'updated_at'        => new DateTime
            ],
            [
                'role_display_name' => 'User',
                'role_description'  => 'A regular User',
                'role_slug'         => 'user',
                'created_at'        => new DateTime,
                'updated_at'        => new DateTime
            ],
            [
                'role_display_name' => 'Banned',
                'role_description'  => 'This Role is banned',
                'role_slug'         => 'banned',
                'created_at'        => new DateTime,
                'updated_at'        => new DateTime
            ],

        ];

       DB::table('roles')->insert($roles);
    }

}