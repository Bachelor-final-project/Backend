<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            ['name' => "USD", 'tenant_id' => 1],
            ['name' => "NIS", 'tenant_id' => 1],
        ]);
    }
}
