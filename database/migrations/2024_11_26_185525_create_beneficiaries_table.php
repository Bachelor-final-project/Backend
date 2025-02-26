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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('national_id')->unique();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('dob')->nullable();
            $table->string('father_national_id')->nullable();
            $table->integer('num_of_family_members')->default(0);
            $table->integer('social_status')->default(0);
            $table->string('address', 512)->nullable();
            
            // $table->unsignedBigInteger('warehouse_id');
            // $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('beneficiaries');
    }
};
