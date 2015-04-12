<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('poll_votes', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('poll_id');
			$table->unsignedInteger('user_id')->nullable();
			$table->string('vote');
			$table->nullableTimestamps();

			$table->foreign('poll_id')->references('id')->on('polls');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('poll_votes');
	}

}
