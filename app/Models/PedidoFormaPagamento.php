<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoFormaPagamento extends Model
{
    protected $fillable = [
        'Pedidos_idPedidos',
        'Formas_idFormas',
        'troco'
    ];

    public function pagamento(){
        return $this->hasOne(FormaPagamento::class, 'id', 'Formas_idFormas');
    }
}
