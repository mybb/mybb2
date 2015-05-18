<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentClassTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_class', function (Blueprint $table) {
			$table->string('content');
			$table->string('class');

			$table->unique('content');
		});

		Schema::table('permissions', function (Blueprint $table) {
			$table->foreign('content_name')->references('content')->on('content_class');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('permissions', function (Blueprint $table) {
			$table->dropForeign('permissions_content_name_foreign');
		});

		Schema::drop('content_class');
	}
}
