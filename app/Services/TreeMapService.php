<?php

namespace App\Services;

use App\Models\TreeMap;
use App\Models\Order;
use App\Models\Country;
use App\Models\State;
use App\Models\RegionCountry;
use Illuminate\Support\Facades\DB;

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
        //$groupedOrders = $ordersData->groupBy([$type . '_id', 'store_id']);

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
    
    public function createTreeMapData($data)
    {

        $treeMapData = [];
        //ordenar a collection pelo valor total_amount
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
    *
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
