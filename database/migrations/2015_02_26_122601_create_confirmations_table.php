<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfirmationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('confirmations', function (Blueprint $table) {
			$table->string('token', 16)->unique();
			$table->string('type');
			$table->integer('user_id')->unsigned();
			$table->string('newData')->nullable();

			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('confirmations');
	}
}
