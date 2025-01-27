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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');  // English name
            $table->string('name_ar');  // Arabic name
            $table->string('code');     // Currency code (e.g., USD, QAR)
            $table->string('symbol');   // Currency symbol (e.g., $, ر.ق)
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->timestamps();      // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
