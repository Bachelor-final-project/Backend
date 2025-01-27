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
            ['name' => "USD", 'code' => 'usd', 'tenant_id' => 1],
            ['name' => "NIS", 'code' => 'nis', 'tenant_id' => 1],
        ]);
    }
}
