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
        Schema::create('proposal_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_beneficiaries');
    }
};