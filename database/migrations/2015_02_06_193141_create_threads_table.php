<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('threads', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->string('slug')->unique();
			$table->integer('forum_id')->unsigned();
			$table->integer('author_id')->unsigned();
			$table->integer('first_post_id')->unsigned()->nullable();
			$table->integer('last_post_id')->unsigned()->nullable();
			$table->integer('views')->default(0);
			$table->timestamps();

			$table->foreign('forum_id')->references('id')->on('forums');
			$table->foreign('author_id')->references('id')->on('users');
			$table->foreign('first_post_id')->references('id')->on('posts');
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
		Schema::drop('threads');
	}

}
