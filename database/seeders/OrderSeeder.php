<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Order;
use App\Models\User;

use Database\Factories\OrderFactory;



class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = Store::all();
        $user = User::all();
        OrderFactory::new()->allStores($stores, $user);
    }
}
