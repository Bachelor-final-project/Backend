<?php

namespace Database\Seeders;

use App\Models\Currency;
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
        $currencies = [
            ['name_en' => 'US Dollar', 'name_ar' => 'دولار أمريكي', 'code' => 'USD', 'symbol' => '$'],
            ['name_en' => 'Jordanian Dinar', 'name_ar' => 'دينار أردني', 'code' => 'JOD', 'symbol' => 'د.أ'],
            ['name_en' => 'Qatari Rial', 'name_ar' => 'ريال قطري', 'code' => 'QAR', 'symbol' => 'ر.ق'],
            ['name_en' => 'Israeli Shekel', 'name_ar' => 'شيكل', 'code' => 'ILS', 'symbol' => '₪'],
        ];

        foreach ($currencies as $currency) {
            Currency::create([
                'name_en' => $currency['name_en'], // English name
                'name_ar' => $currency['name_ar'], // Arabic name
                'code' => $currency['code'],       // Currency code
                'symbol' => $currency['symbol'],   // Currency symbol
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
