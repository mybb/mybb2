<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModerationLogTables extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('moderation_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->index();
			$table->string('moderation');
			$table->string('source_content_type')->nullable();
			$table->unsignedInteger('source_content_id')->nullable();
			$table->string('destination_content_type')->nullable();
			$table->unsignedInteger('destination_content_id')->nullable();
			$table->boolean('is_reverse');
			$table->string('ip_address');
			$table->nullableTimestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::create('moderation_log_subjects', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('moderation_log_id');
			$table->string('content_type');
			$table->unsignedInteger('content_id');
			$table->nullableTimestamps();

			$table->foreign('moderation_log_id')->references('id')->on('moderation_logs');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('moderation_log_subjects');
		Schema::drop('moderation_logs');
	}

}
