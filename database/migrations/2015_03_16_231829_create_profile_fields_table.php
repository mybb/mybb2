<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProfileFieldsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('profile_fields')) {
			return;
		}

		Schema::create('profile_fields', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('profile_field_group_id')->unsigned()->nullable()->index();
			$table->string('type');
			$table->string('name');
			$table->string('description');
			$table->string('validation_rules')->nullable();
			$table->integer('display_order')->unsigned();
			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasTable('profile_field_options')) {
			Schema::table('profile_field_options', function (Blueprint $table) {
				$table->dropForeign('profile_field_id_foreign');
			});
		}

		if (Schema::hasTable('user_profile_fields')) {
			Schema::table('user_profile_fields', function (Blueprint $table) {
				$table->dropForeign('profile_field_id_foreign');
			});
		}

		Schema::drop('profile_fields');
	}
}
