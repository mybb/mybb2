<?php

use Illuminate\Database\Seeder;

class UserSettingsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('user_settings')->delete();

        $user_settings = [
            [
            	'user_id'         => DB::table('users')->where('name', 'Admin')->pluck('id'),
                'topics_per_page' => 10,
            ],
            [
            	'user_id'         => DB::table('users')->where('name', 'Registered')->pluck('id'),
                'topics_per_page' => 10,
            ],
            [
            	'user_id'         => DB::table('users')->where('name', 'Banned')->pluck('id'),
                'topics_per_page' => 10,
            ],
        ];

       DB::table('user_settings')->insert($user_settings);
    }

}