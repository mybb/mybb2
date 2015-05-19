<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsFirstPostToPostsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(
			'posts',
			function (Blueprint $table) {
				$table->boolean('is_topic_starter')->default(false);
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
			'posts',
			function (Blueprint $table) {
				$table->dropColumn('is_topic_starter');
			}
		);
	}

}
