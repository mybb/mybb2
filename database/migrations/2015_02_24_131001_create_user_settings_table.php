<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserSettingsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_settings', function (Blueprint $table) {
			$table->integer('user_id')->unique()->unsigned()
			      ->nullable(); // Not really nullable but otherwise we would throw errors
			$table->integer('date_format')->nullable(); // TODO: Still use hardcoded versions? Or allow custom ones?
			$table->integer('time_format')->nullable();
			$table->string('timezone', 4)->nullable(); // TODO: Still use our numeric version? Or real timezones?
			$table->integer('dst')->unsigned()->default(2); // Auto detection
			$table->boolean('follow_started_topics')->default(true);
			$table->boolean('follow_replied_topics')->default(true);
			$table->boolean('show_editor')->default(true);
			$table->integer('topics_per_page')->unsigned()->default(20);
			$table->integer('posts_per_page')->unsigned()->default(10);
			$table->integer('style')->unsigned()->nullable();
			$table->string('language', 2)->nullable(); // TODO: are there laravel locals using more than 2 chars?
			$table->boolean('notify_on_like')->default(true);
			$table->boolean('notify_on_quote')->default(true);
			$table->boolean('notify_on_reply')->default(true);
			$table->boolean('notify_on_new_post')->default(true);
			$table->boolean('notify_on_new_comment')->default(true);
			$table->boolean('notify_on_comment_like')->default(true);
			$table->boolean('notify_on_my_comment_like')->default(true);
			$table->boolean('notify_on_comment_reply')->default(true);
			$table->boolean('notify_on_my_comment_reply')->default(true);
			$table->boolean('notify_on_new_message')->default(true);
			$table->boolean('notify_on_reply_message')->default(true);
			$table->boolean('notify_on_group_request')->default(true);
			$table->boolean('notify_on_moderation_post')->default(true);
			$table->boolean('notify_on_report')->default(true);
			$table->boolean('notify_on_username_change')->default(true);
			$table->integer('notification_mails')->unsigned()->default(0); // No emails
			$table->boolean('showonline')->default(true);
			$table->boolean('receive_messages')->default(true);
			$table->boolean('block_blocked_messages')->default(true);
			$table->boolean('hide_blocked_posts')->default(true);
			$table->boolean('only_buddy_messages')->default(false);
			$table->boolean('receive_email')->default(true);
			$table->integer('dob_privacy')->unsigned()->default(2);
			$table->integer('dob_visibility')->unsigned()->default(1);

			$table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('style')->references('id')->on('styles');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_settings');
	}
}
