<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'region_country_id',
        'name',
        'uf',
        'info'
    ];

    //relacionamentos
    public function regionCountry()
    {
        return $this->belongsTo(RegionCountry::class);
    }
}
