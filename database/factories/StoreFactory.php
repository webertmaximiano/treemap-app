<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\State;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'country_id' => 1, // Replace with actual country IDs
            'region_country_id' => fake()->randomNumber(1), // Replace with actual region IDs
            'state_id' => fake()->randomNumber(1), // Replace with actual state IDs
        ];
    }

    public function allStates()
    {
        $states = State::with('regionCountry')->get();

        $stores = $states->map(function ($state) {
            $storeCount = random_int(1, 5);

            return Store::factory()
                ->count($storeCount)
                ->state([
                    'state_id' => $state->id,
                    'region_country_id' => $state->regionCountry->id,
                ])
                ->create();
        });

        return $stores->flatten();
    }
}
