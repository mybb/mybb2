<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddApprovedToPostsAndTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->boolean('approved')->default(1)->index();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('approved')->default(1)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('approved');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('approved');
        });
    }
}
