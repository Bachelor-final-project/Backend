<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entities')->insert([
            ['name' => "إغاثة غزة", "supervisor_id" => 2, "donating_form_path" => "help-gaza", 'tenant_id' => 1],
            ['name' => "مبادرة إعمار غزة", "supervisor_id" => 3, "donating_form_path" => "rebuild-gaza", 'tenant_id' => 1],
        ]);

    }
}
