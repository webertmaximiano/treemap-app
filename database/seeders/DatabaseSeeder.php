<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create(); // criando 10 usuarios

        // Chamada para Criar Pais, Regiao e Estados
        $this->call(CountryRegionStateSeeder::class);
      
        // Chamada para gerar lojas para os estados
        $this->call(StoresSeeder::class);

        // Chamada para gerar pedidos para lojas
        $this->call(OrderSeeder::class);
    }
}
