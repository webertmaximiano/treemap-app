<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\TreeMap;
use App\Models\Store;
use App\Models\State;
use App\Models\User;


use App\Services\TreeMapService;
use Database\Seeders\CountryRegionStateSeeder;
use Database\Factories\StoreFactory;
use Database\Factories\OrderFactory;

class TreeMapServiceTest extends TestCase
{
    // metodo para preparar os dados por Local
    // pegar o root country
    // pegar os filhos states
    // pegar lojas
    // pegar o volume de vendas total de cada loja no mes
    use RefreshDatabase;

    private $stores;
    private $orders;

    public function setUp(): void
    {
        parent::setUp();

        // Execute os seeders de pais, regiões e estados povoando as tabelas
        $this->seed(CountryRegionStateSeeder::class);//ok

        // criar lojas para cada estados
        $this->stores = StoreFactory::new()->allStates(); //ok
       
        // Cria pedidos para cada loja
        $this->orders = OrderFactory::new()->allStores($this->stores);
        
    }

    
    // metodo para preparar os dados por Local
    public function test_generate_tree_map_report_locale()
    {
        $service = new TreeMapService();

        // Gerar o relatório para um estado específico (ajuste conforme necessário)
        $type = 'state';
        $state = $this->stores->first()->state; // Ajuste conforme a lógica de estados e lojas
        $month = now()->format('Y-m');

        $treeMap = $service->generateTreeMapReportLocale($type, $state->name, $month);

        // Verificações
        $this->assertInstanceOf(TreeMap::class, $treeMap); // confere se objetos sao de mesma classe
        $this->assertEquals('State Report for ' . $month, $treeMap->name); // nome do relatorio
        $this->assertNotEmpty($treeMap->reportData); //nao vazio

        $reportData = json_decode($treeMap->reportData, true); //convert o json
        
        $this->assertIsArray($reportData); //verifica se e um array
    }

    
}
