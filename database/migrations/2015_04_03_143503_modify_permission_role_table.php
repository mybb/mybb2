<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPermissionRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// We need to make sure all old data gets deleted so drop and recreate it as Laravel's schema builder doesn't have a truncate command
		Schema::dropIfExists('permission_role');

		Schema::create('permission_role', function (Blueprint $table) {
			$table->unsignedInteger('permission_id');
			$table->unsignedInteger('role_id');
			$table->integer('value');
			$table->unsignedInteger('content_id')->nullable();

			$table->foreign('permission_id')->references('id')->on('permissions');
			$table->foreign('role_id')->references('id')->on('roles');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('permission_role');

		Schema::create('permission_role', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('permission_id');
			$table->integer('role_id');
		});
	}

}
