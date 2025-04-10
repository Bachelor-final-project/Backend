<?php

namespace Database\Factories;

use App\Models\Tenant;
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
            'tenant_id' => $this->faker->randomElement(Tenant::pluck('id')->toArray()), // Random tenant_id from existing currencies
            'location' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
