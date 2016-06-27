<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileFieldGroupTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_field_groups', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('profile_fields', function (Blueprint $table) {
            $table->foreign('profile_field_group_id')->references('id')->on('profile_field_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_fields', function (Blueprint $table) {
            $table->dropForeign(['profile_field_group_id']);
        });
        Schema::drop('profile_field_groups');
    }
}
