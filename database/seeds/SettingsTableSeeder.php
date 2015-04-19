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
			['name' => 'wio.minutes'],
			['name' => 'wio.refresh'],
			['name' => 'captcha.method'],
			['name' => 'captcha.ayah_public_key'],
			['name' => 'captcha.ayah_private_key'],
			['name' => 'captcha.recaptcha_public_key'],
			['name' => 'captcha.recaptcha_private_key'],
			['name' => 'captcha.nocaptcha_public_key'],
			['name' => 'captcha.nocaptcha_private_key'],
			['name' => 'user.date_format'],
			['name' => 'user.time_format'],
			['name' => 'user.timezone'],
			['name' => 'user.dst'],
			['name' => 'user.follow_started_topics'],
			['name' => 'user.follow_replied_topics'],
			['name' => 'user.show_editor'],
			['name' => 'user.topics_per_page'],
			['name' => 'user.posts_per_page'],
			['name' => 'user.style'],
			['name' => 'user.language'],
			['name' => 'user.notify_on_like'],
			['name' => 'user.notify_on_quote'],
			['name' => 'user.notify_on_reply'],
			['name' => 'user.notify_on_new_post'],
			['name' => 'user.notify_on_new_comment'],
			['name' => 'user.notify_on_comment_like'],
			['name' => 'user.notify_on_my_comment_like'],
			['name' => 'user.notify_on_comment_reply'],
			['name' => 'user.notify_on_my_comment_reply'],
			['name' => 'user.notify_on_new_message'],
			['name' => 'user.notify_on_reply_message'],
			['name' => 'user.notify_on_group_request'],
			['name' => 'user.notify_on_moderation_post'],
			['name' => 'user.notify_on_report'],
			['name' => 'user.notify_on_username_change'],
			['name' => 'user.notification_mails'],
			['name' => 'user.showonline'],
			['name' => 'user.receive_messages'],
			['name' => 'user.block_blocked_messages'],
			['name' => 'user.hide_blocked_posts'],
			['name' => 'user.only_buddy_messages'],
			['name' => 'user.receive_email'],
			['name' => 'user.dob_privacy'],
			['name' => 'user.dob_visibility'],
            ['name' => 'post.likes_to_show'],
            ['name' => 'likes.per_page'],
            ['name' => 'user_activity.per_page'],
            ['name' => 'user_profile.activity_per_page'],
		]);

		DB::table('setting_values')->insert([
			[
				'setting_id' => DB::table('settings')->where('name', 'general.board_name')->pluck('id'),
				'value' => 'MyBB 2.0 Test Install'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'general.board_desc')->pluck('id'),
				'value' => 'MyBB 2.0 Test Install'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'wio.minutes')->pluck('id'),
				'value' => '15'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'wio.refresh')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.date_format')->pluck('id'),
				'value' => 'default'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.time_format')->pluck('id'),
				'value' => 'default'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.timezone')->pluck('id'),
				'value' => 'default'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.dst')->pluck('id'),
				'value' => '2'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.follow_started_topics')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.follow_replied_topics')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.show_editor')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.topics_per_page')->pluck('id'),
				'value' => '20'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.posts_per_page')->pluck('id'),
				'value' => '10'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.style')->pluck('id'),
				'value' => 'default'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.language')->pluck('id'),
				'value' => 'en'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_like')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_quote')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_reply')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_new_post')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_new_comment')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_comment_like')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_my_comment_like')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_comment_reply')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_my_comment_reply')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_new_message')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_reply_message')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_group_request')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_moderation_post')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_report')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notify_on_username_change')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.notification_mails')->pluck('id'),
				'value' => '0'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.showonline')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.receive_messages')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.block_blocked_messages')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.hide_blocked_posts')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.only_buddy_messages')->pluck('id'),
				'value' => '0'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.receive_email')->pluck('id'),
				'value' => '1'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.dob_privacy')->pluck('id'),
				'value' => '2'
			],
			[
				'setting_id' => DB::table('settings')->where('name', 'user.dob_visibility')->pluck('id'),
				'value' => '1'
			],
            [
                'setting_id' => DB::table('settings')->where('name', 'post.likes_to_show')->pluck('id'),
                'value' => 3,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'likes.per_page')->pluck('id'),
                'value' => 10,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user_activity.per_page')->pluck('id'),
                'value' => 10,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user_profile.activity_per_page')->pluck('id'),
                'value' => 20,
            ]

		]);
	}
}
