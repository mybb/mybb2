<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationUserTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conversation_users', function (Blueprint $table) {
			$table->unsignedInteger('conversation_id');
			$table->unsignedInteger('user_id');
			$table->timestamp('last_read')->nullable();
			$table->boolean('has_left')->default(false);
			$table->boolean('ignores')->default(false);

			$table->foreign('conversation_id')->references('id')->on('conversations');
			$table->foreign('user_id')->references('id')->on('users');

			$table->unique(['conversation_id', 'user_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('conversation_users');
	}

}
