<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\PaymentMethod;
use App\Models\Proposal;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'donor_id' => Donor::factory(), // Assuming you're using a Donor factory
            'currency_id' => $this->faker->randomElement(Currency::pluck('id')->toArray()), // Random currency_id from existing currencies
            'proposal_id' => $this->faker->randomElement(Proposal::pluck('id')->toArray()), // Random proposal_id from existing currencies
            'payment_method_id' => $this->faker->randomElement(PaymentMethod::pluck('id')->toArray()), // Random proposal_id from existing currencies
            'tenant_id' => $this->faker->randomElement(Tenant::pluck('id')->toArray()), // Random tenant_id from existing currencies
            'amount' => $this->faker->randomFloat(2, 10, 1000), // Random amount between 10 and 1000
            'status' => $this->faker->randomElement([0, 2, 3]), // Random status: 0, 2, or 3
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'), // Random date between 2 months ago and now
            'updated_at' => now(),
        ];
    }
}
