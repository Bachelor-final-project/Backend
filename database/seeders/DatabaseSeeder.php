<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Tenant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Bhuvidya\Countries\CountriesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tenant::factory(1)->create([
            'name' => 'Dar_AlQuran'
        ]);
        User::factory(10)->create();

        User::factory()->create([
            'email' => 'a@a.com',
            'status' => 1,
            'type' => 1
        ]);
        User::factory()->create([
            'email' => 'b@b.com',
            'type' => 2
        ]);
        User::factory()->create([
            'email' => 'c@c.com',
            'type' => 3
        ]);
        User::factory()->create([
            'email' => 'd@d.com',
            'type' => 4
        ]);
        User::factory()->create([
            'email' => 'f@f.com',
            'type' => 5
        ]);

        $this->call([
            CountriesSeeder::class,
            CurrencySeeder::class,
            ProposalTypeSeeder::class,
            AreaSeeder::class,
            EntitySeeder::class,
            ProposalSeeder::class,
            PaymentMethodSeeder::class,
            DonationSeeder::class,
            UnitSeeder::class,
            ItemSeeder::class,
            WarehouseSeeder::class,
            WarehouseTransactionSeeder::class,
        ]);
        Country::where('name', 'like', 'Israel')->delete();
        
    }
}
