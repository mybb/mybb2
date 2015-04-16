<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conversations_messages', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('conversation_id');
			$table->unsignedInteger('author_id');
			$table->text('message');
			$table->text('message_parsed');
			$table->nullableTimestamps();

			$table->foreign('conversation_id')->references('id')->on('conversations');
			$table->foreign('author_id')->references('id')->on('users');
		});

		Schema::table('conversations', function (Blueprint $table) {
			$table->foreign('last_message_id')->references('id')->on('conversations_messages');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('conversations', function (Blueprint $table) {
			$table->dropForeign('conversations_last_message_id_foreign');
		});

		Schema::drop('conversations_messages');
	}

}
