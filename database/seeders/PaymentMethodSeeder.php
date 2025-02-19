<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            ['name_ar' => "كليك",'name_en' => "cliq", 'entity_id'=> 1, 'tenant_id' => 1],
            ['name_ar' => "نقدي",'name_en' => "cash", 'entity_id'=> 1, 'tenant_id' => 1],
            ['name_ar' => "وسيط",'name_en' => "Intermediary", 'entity_id'=> 1, 'tenant_id' => 1],
            ['name_ar' => "كليك",'name_en' => "cliq", 'entity_id'=> 2, 'tenant_id' => 1],
            ['name_ar' => "نقدي",'name_en' => "cash", 'entity_id'=> 2, 'tenant_id' => 1],
            ['name_ar' => "وسيط",'name_en' => "Intermediary", 'entity_id'=> 2, 'tenant_id' => 1],
        ]);
    }
}
