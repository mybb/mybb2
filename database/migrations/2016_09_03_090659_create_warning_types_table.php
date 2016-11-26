<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarningTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warning_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reason');
            $table->integer('points');
            $table->integer('expiration_multiple');
            $table->string('expiration_type');
            $table->tinyInteger('must_acknowledge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('warning_types');
    }
}
