<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyPermissionsTable extends Migration
{

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
