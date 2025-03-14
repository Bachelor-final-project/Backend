<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('document_nickname')->nullable();
            $table->unsignedBigInteger('donor_id')->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->float('amount');
            $table->unsignedBigInteger('recipient_id')->nullable(); // (user_id)
            $table->integer('status')->default(0); // [0 => 'hold/pending', 2 => approved, 3 => 'denied']
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
};
