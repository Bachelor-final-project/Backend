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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id')->nullable();
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->integer('status')->default(3); // [1 => 'accepted, 2 => 'unaccepted', 3 => 'pending', 4 => 'preparing', 8 => 'done']
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
};
