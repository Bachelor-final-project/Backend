<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'bio' => $this->faker->text,
            'status' => $this->faker->randomElement([1, 2, 3, 4]),
            'location' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
