<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace');
            $table->string('frequency');
            $table->string('name');
            $table->string('desc');
            $table->unsignedInteger('last_run')->default(0);
            $table->unsignedInteger('next_run')->default(0);
            $table->boolean('enabled')->default(true);
            $table->boolean('logging')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
