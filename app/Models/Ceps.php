<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceps extends Model
{
    protected $fillable = [
        'CEP',
        'Logradouro',
        'Complemento',
        'Local',
        'Bairro',
        'taxaEntrega',
        'vlr_minimo_pedido'
    ];
}
