<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// We need to make sure all old data gets deleted so drop and recreate it as Laravel's schema builder doesn't have a truncate command
		Schema::dropIfExists('permissions');

		Schema::create('permissions', function (Blueprint $table) {
			$table->increments('id');
			$table->string('permission_name');
			$table->string('content_name')->nullable();
			$table->integer('default_value');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('permissions');

		Schema::create('permissions', function (Blueprint $table) {
			$table->increments('id');
			$table->string('permission_display')->nullable();
			$table->string('permission_slug');
			$table->nullableTimestamps();
		});
	}

}
