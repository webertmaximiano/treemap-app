<?php

namespace App\Services;

use App\Models\TreeMap;
use App\Models\Order;

class TreeMapService
{    
     /**
     * Gera um relatório de TreeMap com base na localidade especificada.
     *
     * @param string $type Tipo de localidade (country, state, region)
     * @param mixed $identifier Identificador da localidade (id do país, estado, ou região)
     * @param string $month Mês do relatório no formato 'Y-m'
     * @return TreeMap
     */
    public function generateTreeMapReportLocale(string $type, $identifier, string $month)
    {
        $ordersQuery = Order::where('create_at', 'like', "$month%");

        switch ($type) {
            case 'country':
                $ordersQuery->where('country_id', $identifier);
                break;

            case 'state':
                $ordersQuery->where('state_id', $identifier);
                break;

            case 'region':
                $ordersQuery->where('region_country_id', $identifier);
                break;

            default:
                throw new \InvalidArgumentException("Tipo de localidade inválido: $type");
        }

        $ordersData = $ordersQuery->get();
        $treeMapData = $this->buildTreeMapData($ordersData, $type);

        $treeMap = TreeMap::create([
            'parent_id' => null,
            'name' => ucfirst($type) . " Report for $month",
            'value' => $ordersData->sum('amount'),
            'level' => 0,
            'color' => '#000000', // Cor padrão, pode ser ajustado
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
        $groupedOrders = $ordersData->groupBy([$type . '_id', 'store_id']);

        foreach ($groupedOrders as $locationId => $stores) {
            $locationTotal = $stores->sum('amount');
            $locationData = [
                'name' => $this->getLocationName($type, $locationId),
                'value' => $locationTotal,
                'children' => []
            ];

            foreach ($stores as $storeId => $storeorders) {
                $storeTotal = $storeorders->sum('amount');
                $locationData['children'][] = [
                    'name' => $this->getStoreName($storeId),
                    'value' => $storeTotal
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
                return DB::table('states')->where('id', $id)->value('name');

            case 'region':
                return DB::table('regions')->where('id', $id)->value('name');

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
}