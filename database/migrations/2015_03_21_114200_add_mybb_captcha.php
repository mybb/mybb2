<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMybbCaptcha extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('captcha', function (Blueprint $table) {
			$table->string('imagehash', 32)->unique();
			$table->string('imagestring', 8);
			$table->timestamp('created_at')->nullable();
			$table->boolean('used')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('captcha');
	}

}
