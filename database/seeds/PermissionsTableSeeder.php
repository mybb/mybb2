<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        $permissions = [
            [
                'permission_display'    => 'AdmiCP Access',
                'permission_slug'       => 'admin_access',
            ],
            [
                'permission_display'    => 'Site Access',
                'permission_slug'       => 'site_access',
            ],
        ];

       DB::table('permissions')->insert($permissions);
    }

}