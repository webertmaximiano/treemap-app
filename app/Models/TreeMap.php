<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreeMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', //Identificador do pai na hierarquia (nulo para o nó raiz).
        'name', // Nome do nó (Categoria, item, tipo)
        'value', // Valor numérico associado ao nó (utilizado para determinar o tamanho do retângulo no treemap)
        'level', // Nível na hierarquia (para facilitar consultas).
        'color', // cor atribuida
        'status', // ativo, inativo mais de um ano inativar servico de limpeza exclui os inativos 
        'path_file', //caminho do arquivo se for gerado pdf
        'reportData', //JSON com os dados associado ao no para montagem da arvore
        'ratio',// proporcao em relacao ao valor do pai

    ];

     // Define a relação com o nó pai
     public function parent()
     {
         return $this->belongsTo(TreeMap::class, 'parent_id');
     }
 
     // Define a relação com os nós filhos
     public function children()
     {
         return $this->hasMany(TreeMap::class, 'parent_id');
     }
 
     // Escopo para nós ativos
     public function scopeActive($query)
     {
         return $query->where('status', 'ativo');
     }
}
