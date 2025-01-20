<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachable_type')->nullable()->index();
            $table->unsignedBigInteger('attachable_id')->nullable()->index();
            $table->integer('attachment_type')->default('1');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('filename');
            $table->string('path');
            $table->string('file_extension', 10);
            $table->integer('filesize');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
};
