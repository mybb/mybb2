<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('user_id');
	        $table->unsignedInteger('attachment_type_id');
	        $table->string('title');
	        $table->text('description')->nullable();
	        $table->string('file_name');
	        $table->string('file_path');
	        $table->integer('file_size'); // The size of the file, in bytes.
			$table->string('file_hash', 32); // md5 hash of the file. An md5 hash is 128 bits = 32 hex chars.
	        $table->integer('num_downloads')->default(0);
	        $table->softDeletes();
            $table->timestamps();

	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('attachment_type_id')->references('id')->on('attachment_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attachments');
    }
}
