<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('polls', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('topic_id');
			$table->unsignedInteger('user_id')->nullable();
			$table->string('question');
			$table->unsignedInteger('num_options');
			$table->json('options');
			$table->boolean('is_closed')->default(false);
			$table->boolean('is_multiple')->default(false);
			$table->unsignedInteger('max_options');
			$table->boolean('is_public')->default(false);
			$table->timestamp('end_at')->nullable();
			$table->nullableTimestamps();

			$table->foreign('topic_id')->references('id')->on('topics');
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('topics', function (Blueprint $table) {
			$table->boolean('has_poll')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('polls');

		Schema::table('topics', function (Blueprint $table) {
			$table->dropColumn('has_poll');
		});
	}

}
