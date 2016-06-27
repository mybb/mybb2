<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddNumPostsToUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('num_posts')->unsigned()->default(0);
            $table->integer('num_topics')->unsigned()->default(0);
            $table->string('avatar')->default('');
            $table->string('dob', 10)->default('1-1-');
            $table->string('usertitle')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'num_posts',
                'num_topics',
                'avatar',
                'dob',
                'usertitle',
            ]);
        });
    }
}
