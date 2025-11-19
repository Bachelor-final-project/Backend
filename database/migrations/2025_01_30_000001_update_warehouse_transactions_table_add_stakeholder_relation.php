<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->dropColumn('merchant_beneficiary_name');
            $table->unsignedBigInteger('warehouse_stakeholder_id')->nullable()->after('transaction_type');
            $table->foreign('warehouse_stakeholder_id')->references('id')->on('warehouse_stakeholders');
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->dropForeign(['warehouse_stakeholder_id']);
            $table->dropColumn('warehouse_stakeholder_id');
            $table->string('merchant_beneficiary_name')->nullable()->after('transaction_type');
        });
    }
};