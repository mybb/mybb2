<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('content_id')->nullable();
            $table->string('content_type')->nullable();
            $table->unsignedInteger('issued_by');
            $table->string('reason');
            $table->integer('points');
            $table->text('snapshot')->nullable();
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('must_acknowledge')->default(false);
            $table->boolean('expired')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->unsignedInteger('revoked_by')->nullable();
            $table->text('revoke_reason')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('issued_by')->references('id')->on('users');
            $table->foreign('revoked_by')->references('id')->on('users');
            $table->index(['content_id', 'content_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('warnings');
    }
}
