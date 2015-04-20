<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SoftDeleteToPostsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function (Blueprint $table) {
			$table->softDeletes();
		});
		Schema::table('topics', function (Blueprint $table) {
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function (Blueprint $table) {
			$table->dropSoftDeletes();
		});
		Schema::table('topics', function (Blueprint $table) {
			$table->dropSoftDeletes();
		});
	}
}
