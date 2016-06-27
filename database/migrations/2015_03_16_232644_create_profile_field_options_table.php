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

class CreateProfileFieldOptionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_field_options', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('profile_field_id')->unsigned()->index();
            $table->string('name');
            $table->string('value');
            $table->nullableTimestamps();

            $table->foreign('profile_field_id')->references('id')->on('profile_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_field_options');
    }
}
