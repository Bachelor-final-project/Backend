<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Tenant;
use App\Models\Unit;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'unit_id' => $this->faker->randomElement(Unit::pluck('id')->toArray()), // Generates a Unit model and links it
            'tenant_id' => $this->faker->randomElement(Tenant::pluck('id')->toArray()), // Random tenant_id from existing currencies
            'description' => $this->faker->sentence,
            'estimated_price' => $this->faker->randomFloat(2, 1, 100), // Random price between 1 and 100
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
