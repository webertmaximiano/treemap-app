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
use Database\Seeders\DatabaseSeeder;
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
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        // Execute os seeders de pais, regiões e estados povoando as tabelas
        $this->seed(DatabaseSeeder::class);//ok
        $this->service = new TreeMapService();
    }


    // metodo para preparar os dados por Local
    public function test_generate_tree_map_report_locale()
    {

        // Gerar o relatório para um estado específico (ajuste conforme necessário)
        $type = 'state';
        $state = 'Rio de Janeiro'; // Ajuste conforme a lógica de estados e lojas
        $month = now()->format('Y-m');

        $treeMap = $this->service->generateTreeMapReportLocale($type, $state, $month);

        // Verificações
        $this->assertInstanceOf(TreeMap::class, $treeMap); // confere se objetos sao de mesma classe
        $this->assertEquals('State Report for ' . $month, $treeMap->name); // nome do relatorio
        $this->assertNotEmpty($treeMap->reportData); //nao vazio

        $reportData = json_decode($treeMap->reportData, true); //convert o json

        $this->assertIsArray($reportData); //verifica se e um array
    }

    public function testGenerateTreeMapData()
    {
        // Preparando os dados do banco de dados
        $state = State::where('name', 'Acre')->first();
        $stores = Store::where('state_id', $state->id)->get();
        $orders = Order::all();

        $result = $this->service->generateTreeMapData($orders);
        
       $this->assertEquals($result->count(), 26); // 26 estados
        
    }

}
