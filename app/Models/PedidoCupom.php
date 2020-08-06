<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoCupom extends Model
{
    protected $fillable = [
        'Pedidos_idPedidos',
        'Cupoms_idCupoms',
        'valor'
    ];
}
