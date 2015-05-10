<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollVotesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('poll_votes', function (Blueprint $table) {
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
