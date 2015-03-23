<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profile_fields', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->index();
			$table->integer('profile_field_id');
			$table->text('value');
			$table->nullableTimestamps();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('profile_field_id')->references('id')->on('profile_fields');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_profile_fields');
	}
}
