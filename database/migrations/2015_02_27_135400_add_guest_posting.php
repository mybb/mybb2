<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGuestPosting extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->change();
            $table->string('username')->nullable()->after('user_id');
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->change();
            $table->string('username')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->dropColumn('username');
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->dropColumn('username');
        });
    }
}
