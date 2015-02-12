<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permission_role')->delete();

        $permissions = [
            [
                'permission_id' => '1',
                'role_id'       => '1',
            ],
            [
                'permission_id' => '2',
                'role_id'       => '1',
            ],
            [
                'permission_id' => '2',
                'role_id'       => '2',

            ],
        ];

       DB::table('permission_role')->insert($permissions);
    }

}