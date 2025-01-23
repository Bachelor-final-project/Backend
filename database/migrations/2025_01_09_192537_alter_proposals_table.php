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
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['donor_id']);
            $table->string('proposal_effects')->nullable();
            $table->decimal('cost', 2);
            $table->decimal('share_cost', 2);
            $table->integer('expected_benificiaries_count');
            $table->integer('execution_place');
            $table->date('execution_date');
            $table->date('publishing_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
