<?php

namespace App\Services;

use App\Models\TreeMap;
use App\Models\Order;
use App\Models\Country;
use App\Models\State;
use App\Models\Store;

use App\Models\RegionCountry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TreeMapService
{
    /**
    * Gera um relatório de TreeMap com base na localidade especificada.
    *
    * @param string $type Tipo de localidade (country, state, region)
    * @param mixed $identifier Identificador da localidade (nome país, estado, ou região)
    * @param string $month Mês do relatório no formato 'Y-m'
    * @return TreeMap
    */
    public function generateTreeMapReportLocale(string $type, $identifier, string $month)
    {
        $ordersQuery = Order::where('created_at', 'like', "$month%");

        switch ($type) {
            case 'country':
                $country = Country::where('name', $identifier)->first();
                if ($country) {
                    $ordersQuery->whereHas('store.state.regionCountry.country', function ($query) use ($country) {
                        $query->where('id', $country->id);
                    });
                }
                break;

            case 'state':
                $state = State::where('name', $identifier)->first();
                if ($state) {
                    $ordersQuery->whereHas('store.state', function ($query) use ($state) {
                        $query->where('id', $state->id);
                    });
                }
                break;

            case 'region':
                $region = RegionCountry::where('name', $identifier)->first();
                if ($region) {
                    $ordersQuery->whereHas('store.state.regionCountry', function ($query) use ($region) {
                        $query->where('id', $region->id);
                    });
                }
                break;

            default:
                throw new \InvalidArgumentException("Tipo de localidade inválido: $type");
        }

        $ordersData = $ordersQuery->get();
        $treeMapData = $this->buildTreeMapData($ordersData, $type);

        $treeMap = TreeMap::create([
            'parent_id' => null,
            'name' => ucfirst($type) . " Report for $month",
            'value' => $ordersData->sum('total_amount'),
            'level' => 0,
            'color' => $this->getColor('root'), // Cor padrão, pode ser ajustado no metodo
            'status' => 'ativo',
            'reportData' => json_encode($treeMapData),
            'ratio' => 100 //proporcao 100
        ]);

        return $treeMap;
    }

    /**
     * Constrói os dados do TreeMap a partir dos pedido.
     *
     * @param \Illuminate\Support\Collection $ordersData
     * @param string $type
     * @return array
     */
    private function buildTreeMapData($ordersData, $type)
    {
        $treeMapData = [];

        // Agrupa os pedidos por localidade e loja
        $groupedOrders = $ordersData->groupBy(function ($order) use ($type) {
            switch ($type) {
                case 'country':
                    return $order->store->state->regionCountry->country;
                case 'state':
                    return $order->store->state->id;
                case 'region':
                    return $order->store->state->regionCountry->id;
                default:
                    return null;
            }
        });

        foreach ($groupedOrders as $locationId => $stores) {
            //soma o total dos pedidos das lojas do estado
            $locationTotal = $this->calculateChildrenTotalValue($ordersData); //1445.13 ok

            $locationData = [
                'name' => $this->getLocationName($type, $locationId),
                'value' => $locationTotal,
                'color' => $this->getColor('root'),
                'children' => []
            ];

            foreach ($stores as $storeId => $storeorders) {
                $storeTotal = $storeorders->total_amount;
                $locationData['children'][] = [
                    'name' => $this->getStoreName($storeorders->store_id),
                    'value' => $storeTotal,
                    'color' => $this->getColor('children'),
                ];
            }

            $treeMapData[] = $locationData;
        }

        return $treeMapData;
    }


    /**
     * Retorna o nome da localidade com base no tipo e identificador.
     *
     * @param string $type
     * @param int $id
     * @return string
     */
    private function getLocationName($type, $id)
    {
        switch ($type) {
            case 'country':
                return DB::table('countries')->where('id', $id)->value('name');

            case 'state':
                $name = DB::table('states')->where('id', $id)->value('name');
                return DB::table('states')->where('id', $id)->value('name');

            case 'region':
                return DB::table('region_countries')->where('id', $id)->value('name');

            default:
                return 'Unknown';
        }
    }

    /**
     * Retorna o nome da loja com base no identificador.
     *
     * @param int $id
     * @return string
     */
    private function getStoreName($id)
    {
        return DB::table('stores')->where('id', $id)->value('name');
    }

    /**
     * Retorna a cor a ser usada no retangulo.
     *
     * @param string $type
     * @return string
     */
    private function getColor($type)
    {
        if ($type === 'root') {

            return 'blue';
        } else {
            return 'green';
        }
    }

    /**
    * Retorna o tamanho do item a ser usada no retangulo.
    * falta terminar 
    * @param array $item,
    * @return array largura altura
    */
    private function getSize($item)
    {
        // Calcular as dimensões iniciais do retângulo do estado
        $width = 800; // Largura inicial do estado
        $height = 600; // Altura inicial do estado
        $x = 0;
        $y = 0;
    }


    //Em Uso
    public function generateTreeMapData($data)
    {
        //Array pro TreeMap ser renderizado
        $treeMapData = [];

        // Passo 1: Ordenar a colecao das lojas com o valor total dos pedidos
        $storesWithTotals = $this->groupByStore($data); 

        // Passo 2: Agrupar as lojas por estado
        $statesWithTotals = $this->groupByState($storesWithTotals);

        // Passo 3: calcular o total dos pedidos por estado somando cada total de loja
        $totalValue = $statesWithTotals->sum('total_amount');

        //Passo 4: calcular a dimensao do retangulo
        $parentWidth = 800; // pode implementar uma consulta para personalizar
        $parentHeight = 600; // pode implementar uma consulta para personalizar
        $statesWithTotals = $this->calculateDimensions($statesWithTotals, $totalValue, $parentWidth, $parentHeight);

        // Passo 5: Atribuir a posição do retângulo no mapa
        $statesWithTotals = $this->assignPositions($statesWithTotals);

        return $statesWithTotals;
    }
     //Em Uso
    private function groupByStore($data)
    {
        // Agrupar os pedidos por loja
        $ordersByStore = $data->groupBy('store_id');

        // Pegar as lojas e o valor total de pedidos de cada uma
        $storesWithTotals = $ordersByStore->map(function ($storeOrders) {
            $firstOrder = $storeOrders->first();
            return [
                'store_id' => $firstOrder['store_id'],
                'store_name' => $firstOrder['store_name'],
                'total_amount' => $storeOrders->sum('total_amount'),
                'children' => $storeOrders->map(function ($order) {
                    return [
                        'order_id' => $order['id'],
                        'order_amount' => $order['total_amount'],
                    ];
                })->toArray(),
            ];
        })->values();

        return $storesWithTotals;
    }
     //Em Uso
    private function groupByState($storesWithTotals)
    {
        // Agrupar as lojas por estado
        $storesGroupedByState = $storesWithTotals->groupBy(function ($store) {
            // Presumindo que você tem um relacionamento de loja para estado
            $storeModel = Store::find($store['store_id']);
            return $storeModel ? $storeModel->state_id : null;
        })->filter(function ($group, $key) {
            // Filtrar qualquer grupo onde a chave (state_id) seja nula
            return $key !== null;
        });

        // Criar a estrutura de dados do estado
        $statesWithTotals = $storesGroupedByState->map(function ($stateStores, $stateId) {
            // Encontrar o estado baseado no ID
            $state = State::find($stateId);

            if ($state) {
                return [
                    'state_id' => $stateId,
                    'state_name' => $state->name,
                    'total_amount' => $stateStores->sum('total_amount'),
                    'children' => $stateStores->map(function ($store) {
                        return [
                            'store_id' => $store['store_id'],
                            'store_name' => $store['store_name'],
                            'total_amount' => $store['total_amount'],
                        ];
                    })->toArray(),
                ];
            }
            return null;
        })->filter();

        return $statesWithTotals;
    }

    //falta resolver porque nao ta modificando a estrutura adicionando as novas propriedades
    private function calculateDimensions(&$data, $totalValue, $parentWidth, $parentHeight)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            return [];
        }

        foreach ($data as &$item) {
            $proportion = $item['total_amount'] / $totalValue;
            $item['width'] = $parentWidth * $proportion;
            $item['height'] = $parentHeight * $proportion;

            if (isset($item['children']) && (is_array($item['children']) || $item['children'] instanceof \Traversable)) {
                $item['children'] = $this->calculateDimensions($item['children'], $item['total_amount'], $item['width'], $item['height']);
            }
        }

        return $data;
    }
    //falta resolver porque nao ta modificando a estrutura adicionando as novas propriedades
    private function assignPositions(&$data)
    {
        $offsetX = 0;
        $offsetY = 0;

        foreach ($data as &$item) {
            // Verificar a presença dos valores width e height
            if (!isset($item['width']) || !isset($item['height'])) {
                // Se width ou height não estiver presente, continue ou defina um valor padrão
                continue; // ou defina um valor padrão
            }

            $item['x'] = $offsetX;
            $item['y'] = $offsetY;

            if (isset($item['children']) && (is_array($item['children']) || $item['children'] instanceof \Traversable)) {
                $offsetChildY = $offsetY;

                foreach ($item['children'] as &$child) {
                    // Verificar a presença dos valores width e height
                    if (!isset($child['width']) || !isset($child['height'])) {
                        // Se width ou height não estiver presente, continue ou defina um valor padrão
                        continue; // ou defina um valor padrão
                    }

                    $child['x'] = $offsetX;
                    $child['y'] = $offsetChildY;
                    $offsetChildY += $child['height'];
                }
            }

            $offsetX += $item['width'];
        }

        return $data;
    }


    private function calculateChildrenTotalValue($childrens)
    {
        return $childrens->sum('total_amount');
    }

    public function generateTreeMapSizeData($data, $parentWidth = 800, $parentHeight = 600)
    {
        $totalValue = array_sum(array_column($data, 'value'));

        foreach ($data as &$item) {
            $proportion = $item['value'] / $totalValue;
            $item['width'] = $parentWidth * $proportion;
            $item['height'] = $parentHeight * $proportion;
            if (isset($item['children'])) {
                $item['children'] = $this->generateTreeMapSizeData($item['children'], $item['width'], $item['height']);
            }
        }
        return $data;
    }

    private function generateChildSizes($children, $parentWidth, $parentHeight)
    {
        $totalValue = array_sum(array_column($children, 'value'));
        $offsetX = 0;
        $offsetY = 0;

        foreach ($children as &$child) {
            $proportion = $child['value'] / $totalValue;
            $child['width'] = $parentWidth * $proportion;
            $child['height'] = $parentHeight * $proportion;
            $child['x'] = $offsetX;
            $child['y'] = $offsetY;

            $offsetY += $child['height'];
        }

        return $children;
    }

}
