<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    //informacoes minimas para demonstracao
    protected $fillable = [
        'name',
        'country_id',
        'region_country_id',
        'state_id',
    ];

    //relacionamentos
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(RegionCountry::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
