<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchlogTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('searchlog', function (Blueprint $table) {
			$table->string('id', 40)->primary();
			$table->boolean('as_topics')->default(1);
			$table->integer('user_id')->unsigned();
			$table->longText('topics');
			$table->longText('posts');
			$table->string('keywords');

			$table->nullableTimestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('searchlog');
	}
}
