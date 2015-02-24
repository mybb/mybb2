<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_settings', function(Blueprint $table)
		{
			$table->integer('user_id')->unique()->unsigned()->nullable(); // Not really nullable but otherwise we would throw errors
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
            $table->integer('notification_mails', false, true)->default(0); // No emails

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
