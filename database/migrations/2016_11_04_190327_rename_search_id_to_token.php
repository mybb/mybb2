<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSearchIdToToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('searchlog', function (Blueprint $table) {
            $table->renameColumn('id', 'token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('searchlog', function (Blueprint $table) {
            $table->renameColumn('token', 'id');
        });
    }
}
