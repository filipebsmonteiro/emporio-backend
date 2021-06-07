<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceps extends Model
{
    protected $fillable = [
        'CEP',
        'logradouro',
        'complemento',
        'local',
        'bairro',
        'taxa_entrega',
        'vlr_minimo_pedido'
    ];
}
