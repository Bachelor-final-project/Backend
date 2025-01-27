<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Currency;
use App\Models\Entity;
use App\Models\Proposal;
use App\Models\ProposalType;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proposal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence, // Random title (sentence)
            'body' => $this->faker->paragraph, // Random body (paragraph)
            'status' => $this->faker->randomElement([1, 2, 3, 8]), // Random status [0 => 'draft', 1 => 'approved', 2 => 'rejected', 3 => 'completed']
            'notes' => $this->faker->text, // Random notes
            'currency_id' => $this->faker->randomElement(Currency::pluck('id')->toArray()), // Random currency_id from existing currencies
            'proposal_effects' => $this->faker->text, // Random proposal effects description
            'cost' => $this->faker->randomFloat(2, 1000, 10000), // Random cost between 1000 and 10000
            'share_cost' => $this->faker->randomFloat(2, 100, 5000), // Random share cost between 100 and 5000
            'expected_benificiaries_count' => $this->faker->numberBetween(10, 1000), // Random expected beneficiaries count between 10 and 1000
            'execution_date' => $this->faker->dateTimeBetween('now', '+2 years'), // Random execution date in the future
            'publishing_date' => $this->faker->dateTimeBetween('-1 month', 'now'), // Random publishing date in the past month
            'entity_id' => $this->faker->randomElement(Entity::pluck('id')->toArray()), // Random entity_id from existing entities
            'proposal_type_id' => $this->faker->randomElement(ProposalType::pluck('id')->toArray()), // Random proposal_type_id from existing proposal types
            'area_id' => $this->faker->randomElement(Area::pluck('id')->toArray()), // Random area_id from existing areas
            'min_documenting_amount' => $this->faker->randomFloat(2, 100, 5000), // Random minimum documenting amount between 100 and 5000
            'tenant_id' => $this->faker->randomElement(Tenant::pluck('id')->toArray()), // Random tenant_id from existing currencies
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'), // Random created_at within the last 2 months
            'updated_at' => now(), // Updated at is set to current time
        ];
    }
}
