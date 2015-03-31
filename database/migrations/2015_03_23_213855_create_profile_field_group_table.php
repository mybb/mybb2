<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileFieldGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_field_groups', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('slug')->unique();
			$table->string('description')->nullable();
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
		Schema::table('profile_fields', function (Blueprint $table) {
//			$table->dropForeign(['profile_field_group_id']);
		});
		Schema::drop('profile_field_groups');
	}

}
