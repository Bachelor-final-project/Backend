<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('national_id')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('type');
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stakeholders');
    }
};