<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Warehouse;
use App\Models\WarehouseTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WarehouseTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'warehouse_id' => $this->faker->randomElement(Warehouse::pluck('id')->toArray()), // Generates a Warehouse model and links it
            'item_id' => $this->faker->randomElement(Item::pluck('id')->toArray()),          // Generates an Item model and links it
            'amount' => $this->faker->numberBetween(1, 100), // Random amount between 1 and 100
            'transaction_type' => $this->faker->randomElement([1, 2]), // Either "in" or "out"
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
