<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileFieldsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('profile_field_groups')->delete();

        $profileFieldGroups = [
            [
                'name' => 'About You',
                'slug' => 'about-you',
            ],
            [
                'name' => 'Contact Details',
                'slug' => 'contact-details',
            ]
        ];

        DB::table('profile_field_groups')->insert($profileFieldGroups);

        DB::table('profile_fields')->delete();

        $profileFields = [
            [
                'type' => 'text',
                'name' => 'Favourite Pet',
                'description' => "What's the name of your favourite pet?",
                'display_order' => 4,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->pluck('id')
            ],
            [
                'type' => 'select',
                'name' => 'Sex',
                'description' => '',
                'display_order' => 1,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->pluck('id')
            ],
            [
                'type' => 'text',
                'name' => 'Location',
                'description' => 'Where in the world do you live?',
                'display_order' => 2,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->pluck('id')
            ],
            [
                'type' => 'textarea',
                'name' => 'Bio',
                'description' => 'Enter a few short details about yourself, your life story etc.',
                'display_order' => 3,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->pluck('id')
            ],
            [
                'type' => 'text',
                'name' => 'Website',
                'description' => '',
                'display_order' => 1,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'contact-details')->pluck('id')
            ],
            [
                'type' => 'text',
                'name' => 'Skype',
                'description' => '',
                'display_order' => 2,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'contact-details')->pluck('id')
            ],
            [
                'type' => 'text',
                'name' => 'Twitter',
                'description' => '',
                'display_order' => 1,
                'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'contact-details')->pluck('id')
            ],
        ];

        DB::table('profile_fields')->insert($profileFields);

        DB::table('profile_field_options')->delete();

        $options = [
            [
                'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->pluck('id'),
                'name' => 'Male',
                'value' => 'Male',
            ],
            [
                'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->pluck('id'),
                'name' => 'Female',
                'value' => 'Female',
            ],
            [
                'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->pluck('id'),
                'name' => 'Undisclosed',
                'value' => 'Undisclosed',
            ],
            [
                'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->pluck('id'),
                'name' => 'Other',
                'value' => 'Other',
            ],
        ];

        DB::table('profile_field_options')->insert($options);
    }
}
