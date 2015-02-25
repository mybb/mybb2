<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('topic_id')->unsigned();
			$table->text('content');
			$table->text('content_parsed'); // Store parsed HTML in DB or not? Would be much quicker, as parser is current bottleneck...
			$table->nullableTimestamps();
			$table->softDeletes();

			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('forums', function (Blueprint $table) {
			$table->foreign('last_post_id')->references('id')->on('posts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('forums', function (Blueprint $table) {
			$table->dropForeign('forums_last_post_id_foreign');
		});

		Schema::drop('posts');
	}

}
