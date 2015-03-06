<?php
/**
 * Settings table seeder, creates settings required for an install to function.
 */

class SettingsTableSeeder extends \Illuminate\Database\Seeder
{
	public function run()
	{
		DB::table('settings')->delete();

		DB::table('settings')->insert([
			['name' => 'general.board_name'],
			['name' => 'general.board_desc'],
		                              ]);

		DB::table('setting_values')->insert([
			['setting_id' => DB::table('settings')->where('name', 'general.board_name')->pluck('id'),
			'value' => 'MyBB 2.0 Test Install'],
			['setting_id' => DB::table('settings')->where('name', 'general.board_desc')->pluck('id'),
			 'value' => 'MyBB 2.0 Test Install'],
		                                    ]);
	}
}
