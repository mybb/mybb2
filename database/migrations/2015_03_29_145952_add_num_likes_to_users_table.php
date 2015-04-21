<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNumLikesToUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(
			'users',
			function (Blueprint $table) {
				$table->unsignedInteger('num_likes_made')->default(0);
			}
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(
			'users',
			function (Blueprint $table) {
				$table->dropColumn('num_likes_made');
			}
		);
	}
}
