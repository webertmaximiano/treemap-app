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

        $data = [
            [
                'name' => $state->name,
                'value' => $stores->sum('total_orders_value'), // Usando o método total_orders_value
                'children' => $stores->map(function ($store) {
                    return [
                        'name' => $store->name,
                        'value' => $store->total_orders_value // Usando o método total_orders_value
                    ];
                })->toArray()
            ]
        ];

        $result = $this->service->generateTreeMapData(collect($data));

        // Teste do parent
        $this->assertEquals(800, $result[0]['width']);
        $this->assertEquals(600, $result[0]['height']);

        // Teste dos filhos
        $children = $result[0]['children'];
        $totalChildValue = array_sum(array_column($children, 'value'));

        // Verifica se os filhos estão corretamente ordenados por valor
        $sortedChildren = collect($children)->sortByDesc('value')->values()->toArray();
        $this->assertEquals($sortedChildren, $children);

        $offsetY = 0;
        foreach ($children as $child) {
            $proportion = $child['value'] / $totalChildValue;
            $expectedWidth = 800 * $proportion;
            $expectedHeight = 600 * $proportion;

            $this->assertEquals($expectedWidth, $child['width']);
            $this->assertEquals($expectedHeight, $child['height']);
            $this->assertEquals(0, $child['x']);
            $this->assertEquals($offsetY, $child['y']);

            $offsetY += $child['height'];
        }
    }

    

}
