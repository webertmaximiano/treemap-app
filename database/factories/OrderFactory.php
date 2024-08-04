<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'store_name' => $this->faker->company,
            'user_id' => User::factory(),
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
        ];
    }

    public function allStores($stores, $users) 
    {
        foreach ($stores as $store) {

            foreach ($users as $user) {

                Order::factory()->create([
                    'store_id' => $store->id,
                    'store_name' => $store->name,
                    'user_id' => $user->id,
                    'total_amount' => $this->faker->randomFloat(2, 50, 500),
                ]);
            }
           
        }
    }
}