<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forums', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->string('slug')->unique();
			$table->text('description');

			// Link forums
			$table->boolean('is_link')->default(false);
			$table->string('link')->nullable();

			// Cached data
			$table->integer('num_topics')->default(0);
			$table->integer('num_posts')->default(0);
			$table->integer('last_post_id')->unsigned()->nullable();
			$table->integer('last_post_user_id')->unsigned()->nullable();

			// Nested sets
			$table->unsignedInteger('left_id');
			$table->unsignedInteger('right_id');
			$table->unsignedInteger('parent_id')->nullable();


			$table->foreign('last_post_user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('forums');
	}
}
