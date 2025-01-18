<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();
        $this->call(CountriesSeeder::class);
        User::factory()->create([
            'email' => 'a@a.com',
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
    }
}
