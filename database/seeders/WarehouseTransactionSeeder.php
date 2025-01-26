<?php

namespace Database\Seeders;

use App\Models\WarehouseTransaction;
use Illuminate\Database\Seeder;

class WarehouseTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WarehouseTransaction::factory(300)->create();
    }
}
