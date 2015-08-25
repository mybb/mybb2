<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClosedToTopicsAndForumsTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('topics', function (Blueprint $table) {
			$table->tinyInteger('closed')->unsigned()->default(0)->index();
		});

		Schema::table('forums', function (Blueprint $table) {
			$table->tinyInteger('closed')->unsigned()->default(0)->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('topics', function (Blueprint $table) {
			$table->dropColumn('closed');
		});

		Schema::table('forums', function (Blueprint $table) {
			$table->dropColumn('closed');
		});
	}
}
