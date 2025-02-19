<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->insert([
            ['name' => "قطاع غزة", 'tenant_id' => 1],
            ['name' => "شمال القطاع", 'tenant_id' => 1],
            ['name' => "جنوب القطاع", 'tenant_id' => 1],
        ]);
    }
}
