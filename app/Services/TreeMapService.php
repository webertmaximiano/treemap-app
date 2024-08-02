<?php

namespace App\Services;

use App\Models\TreeMap;

class TreeMapService
{
    public function generateTreeMapData($data)
    {
        // Construir a árvore (exemplo simplificado com array associativo)
        $tree = $this->buildTree($data);

        // Atribuir níveis e parentes recursivamente
        $this->assignLevelsAndParents($tree);

        // Converter a árvore para um array para inserção no banco de dados
        $treeData = $this->treeToArray($tree);

        // Inserir os dados no banco de dados
        TreeMap::insert($treeData);
    }

    private function buildTree($data)
    {
        // Lógica para construir a árvore a partir dos dados iniciais
        // ...
        return $tree;
    }

    private function assignLevelsAndParents(&$node, $level = 0, $parentId = null)
    {
        $node['level'] = $level;
        $node['parent_id'] = $parentId;

        if (isset($node['children'])) {
            foreach ($node['children'] as &$child) {
                $this->assignLevelsAndParents($child, $level + 1, $node['id']);
            }
        }
    }

    private function treeToArray($tree)
    {
        // Lógica para converter a árvore em um array para inserção
        // ...
        return $treeData;
    }
}