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
                    $ordersQuery->whereHas('store.state.regionCountry.country', function($query) use ($country) {
                        $query->where('id', $country->id);
                    });
                }
                break;

            case 'state':
                $state = State::where('name', $identifier)->first();
                if ($state) {
                    $ordersQuery->whereHas('store.state', function($query) use ($state) {
                        $query->where('id', $state->id);
                    });
                }
                break;

            case 'region':
                $region = RegionCountry::where('name', $identifier)->first();
                if ($region) {
                    $ordersQuery->whereHas('store.state.regionCountry', function($query) use ($region) {
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
            'ratio' => 1
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

        $groupedOrders = $ordersData->groupBy(function($order) use ($type) {
            switch ($type) {
                case 'country':
                    return $order->store->state->regionCountry->country->id;
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
            $locationTotal = $stores->sum('total_amount'); //1445.13 ok

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

            return 'steelblue';
        } else {
            return 'steelblue';
        }
    }
}