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
        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->string('merchant_beneficiary_name')->nullable()->after('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->dropColumn('merchant_beneficiary_name');
        });
    }
};