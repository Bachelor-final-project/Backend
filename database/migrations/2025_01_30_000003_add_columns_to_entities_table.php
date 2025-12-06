<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->integer('country_id')->nullable()->references('id')->on('countries');
            $table->json('home_title')->nullable()->after('country_id');
            $table->json('home_description')->nullable()->after('home_title');
            $table->string('whatsapp_number')->nullable()->after('home_description');
            
        });
    }

    public function down()
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn(['country_id', 'home_title', 'home_description', 'whatsapp_number']);
        });
    }
};