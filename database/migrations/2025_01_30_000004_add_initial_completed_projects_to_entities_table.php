<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->integer('initial_completed_projects')->default(0)->after('whatsapp_number');
        });
    }

    public function down()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('initial_completed_projects');
        });
    }
};