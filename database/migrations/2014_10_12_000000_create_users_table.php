<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('salt')->nullable();
			$table->string('hasher')->nullable();
			$table->rememberToken();
			$table->integer('role_id');
			$table->string('avatar')->default('');
			$table->string('dob', 10)->default('1-1-');
			$table->string('usertitle')->default('');
			$table->integer('num_posts')->unsigned()->default(0);
			$table->integer('num_topics')->unsigned()->default(0);
			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
