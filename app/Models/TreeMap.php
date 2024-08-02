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
        'level' // Nível na hierarquia (para facilitar consultas).
    ];
}
