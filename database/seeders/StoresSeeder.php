<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\Store;


class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = State::all();

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

        //return $stores->flatten();
       // dd($stores);
    }
}
