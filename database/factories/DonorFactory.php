<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name, // Random name
            'gender' => $this->faker->randomElement([1, 2]), // Random gender: 1 for male, 2 for female
            'country_id' => $this->faker->randomElement(Country::pluck('id')->toArray()), // Random country id from existing countries
            'phone' => $this->faker->unique()->phoneNumber, // Random unique phone number
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'), // Random created_at within the last 2 months
            'updated_at' => now(), // Updated at is set to current time
        ];
    }
}
