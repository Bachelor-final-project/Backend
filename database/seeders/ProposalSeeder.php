<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Proposal;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proposal::factory(50)->create();
    }
}
