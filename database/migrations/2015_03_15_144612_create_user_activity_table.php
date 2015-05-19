<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivityTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_activity', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->nullable();;
			$table->string('activity_type');
			$table->unsignedInteger('activity_id');
			$table->json('extra_details')->nullable();
			$table->nullableTimestamps();

			$table->index('user_id');

			// No foreign key, as then activity from unregistered users (eg: people registering) won't work!
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_activity');
	}
}
