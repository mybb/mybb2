<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topics', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->string('slug')->unique();
			$table->integer('forum_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('first_post_id')->unsigned()->nullable();
			$table->integer('last_post_id')->unsigned()->nullable();
			$table->integer('views')->default(0);
			$table->nullableTimestamps();
			$table->softDeletes();

			$table->foreign('forum_id')->references('id')->on('forums');
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('first_post_id')->references('id')->on('posts');
			$table->foreign('last_post_id')->references('id')->on('posts');
		});

		Schema::table('posts', function (Blueprint $table) {
			$table->foreign('topic_id')->references('id')->on('topics');
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
			$table->dropForeign('posts_topic_id_foreign');
		});

		Schema::drop('topics');
	}

}
