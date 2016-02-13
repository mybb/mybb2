<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileFieldsTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('profile_field_options')->delete();
		DB::table('profile_fields')->delete();
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

		$profileFields = [
			[
				'type' => 'text',
				'name' => 'Favourite Pet',
				'description' => "What's the name of your favourite pet?",
				'display_order' => 4,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->first()->id
			],
			[
				'type' => 'select',
				'name' => 'Sex',
				'description' => '',
				'display_order' => 1,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->first()->id
			],
			[
				'type' => 'text',
				'name' => 'Location',
				'description' => 'Where in the world do you live?',
				'display_order' => 2,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->first()->id
			],
			[
				'type' => 'textarea',
				'name' => 'Bio',
				'description' => 'Enter a few short details about yourself, your life story etc.',
				'display_order' => 3,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'about-you')->first()->id
			],
			[
				'type' => 'url',
				'name' => 'Website',
				'description' => 'Must be a valid URL.',
				'display_order' => 1,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'contact-details')
					->first()->id,
				'validation_rules' => 'required|url'
			],
			[
				'type' => 'text',
				'name' => 'Skype',
				'description' => '',
				'display_order' => 2,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'contact-details')
					->first()->id
			],
			[
				'type' => 'text',
				'name' => 'Twitter',
				'description' => 'Must be in the format @username.',
				'display_order' => 1,
				'profile_field_group_id' => DB::table('profile_field_groups')->where('slug', 'contact-details')
					->first()->id,
				'validation_rules' => 'regex:/^@\w+$/'
			],
		];

		foreach ($profileFields as $profileField) {
			\MyBB\Core\Database\Models\ProfileField::create($profileField);
		}

		$options = [
			[
				'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->first()->id,
				'name' => 'Male',
				'value' => 'Male',
			],
			[
				'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->first()->id,
				'name' => 'Female',
				'value' => 'Female',
			],
			[
				'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->first()->id,
				'name' => 'Undisclosed',
				'value' => 'Undisclosed',
			],
			[
				'profile_field_id' => DB::table('profile_fields')->where('name', 'Sex')->first()->id,
				'name' => 'Other',
				'value' => 'Other',
			],
		];

		DB::table('profile_field_options')->insert($options);
	}
}
