<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'email' => 'a@a.com',
            'status' => 1,
            'type' => 1
        ]);
        User::factory()->create([
            'email' => 'b@b.com',
        ]);
        User::factory()->create([
            'email' => 'c@c.com',
        ]);

        $this->call([
            ProposalTypeSeeder::class,
            AreaSeeder::class,
            EntitySeeder::class,
        ]);
    }
}
