<?php

/**
 * Settings table seeder, creates settings required for an install to function.
 *
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */
class SettingsTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        DB::table('settings')->delete();

        DB::table('settings')->insert([
            ['name' => 'general.board_name'],
            ['name' => 'general.board_desc'],
            ['name' => 'general.site_name'],
            ['name' => 'general.site_url'],
            ['name' => 'wio.minutes'],
            ['name' => 'wio.refresh'],
            ['name' => 'captcha.method'],
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
            ['name' => 'memberlist.sort_by'],
            ['name' => 'memberlist.sort_dir'],
            ['name' => 'memberlist.per_page'],
            ['name' => 'conversations.enabled'],
            ['name' => 'conversations.message_order'],
            ['name' => 'warnings.max_points'],
            ['name' => 'warnings.allow_zero'],
            ['name' => 'warnings.allow_custom'],
        ]);

        DB::table('setting_values')->insert([
            [
                'setting_id' => DB::table('settings')->where('name', 'general.board_name')->first()->id,
                'value'      => 'MyBB 2.0 Test Install',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'general.board_desc')->first()->id,
                'value'      => 'MyBB 2.0 Test Install',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'general.site_name')->first()->id,
                'value'      => 'MyBB Home',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'general.site_url')->first()->id,
                'value'      => 'http://www.mybb.com',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'wio.minutes')->first()->id,
                'value'      => '15',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'wio.refresh')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.date_format')->first()->id,
                'value'      => 'default',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.time_format')->first()->id,
                'value'      => 'default',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.timezone')->first()->id,
                'value'      => 'default',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.dst')->first()->id,
                'value'      => '2',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.follow_started_topics')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.follow_replied_topics')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.show_editor')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.topics_per_page')->first()->id,
                'value'      => '20',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.posts_per_page')->first()->id,
                'value'      => '10',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.style')->first()->id,
                'value'      => 'default',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.language')->first()->id,
                'value'      => 'en',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_like')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_quote')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_reply')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_new_post')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_new_comment')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_comment_like')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_my_comment_like')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_comment_reply')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_my_comment_reply')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_new_message')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_reply_message')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_group_request')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_moderation_post')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_report')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notify_on_username_change')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.notification_mails')->first()->id,
                'value'      => '0',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.showonline')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.receive_messages')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.block_blocked_messages')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.hide_blocked_posts')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.only_buddy_messages')->first()->id,
                'value'      => '0',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.receive_email')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.dob_privacy')->first()->id,
                'value'      => '2',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'user.dob_visibility')->first()->id,
                'value'      => '1',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'post.likes_to_show')->first()->id,
                'value'      => 3,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'likes.per_page')->first()->id,
                'value'      => 10,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'memberlist.sort_by')->first()->id,
                'value'      => 'created_at',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'memberlist.sort_dir')->first()->id,
                'value'      => 'asc',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'memberlist.per_page')->first()->id,
                'value'      => 10,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'conversations.enabled')->first()->id,
                'value'      => 1,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'conversations.message_order')->first()->id,
                'value'      => 'desc',
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'warnings.max_points')->first()->id,
                'value'      => 10,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'warnings.allow_zero')->first()->id,
                'value'      => 1,
            ],
            [
                'setting_id' => DB::table('settings')->where('name', 'warnings.allow_custom')->first()->id,
                'value'      => 1,
            ],
        ]);
    }
}
