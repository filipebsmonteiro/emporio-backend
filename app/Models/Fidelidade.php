<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fidelidade extends Model
{
    use SoftDeletes;

    /**
     * @var float
     * Determina a Porcentagem em Float de cada pedido
     * a ser resgatado
     * Ex. 0.1 = 10%   -   0.2 = 20%
     */
    protected $porcentagem          = 0.1;

    /**
     * @var int
     * Determina o mínimo de Pedidos para o resgate.
     * Caso o número for 5
     * o Cliente só poderá resgatar a partir do 6º Pedido.
     *
     * Só serão considerados os pedidos Concluidos!
     */
    protected $minimoResgate        = 0;

    protected $fillable = [
        'valorAcumulado',
        'valorResgate',
        'Clientes_idClientes',
        'Lojas_idLojas',
        'Pedidos_idPedidos'
    ];

    public function getPorcentagem()
    {
        return $this->porcentagem;
    }

    public function getMinimoResgate()
    {
        return $this->minimoResgate;
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Clientes_idClientes', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'Lojas_idLojas', 'id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'Pedidos_idPedidos', 'id');
    }
}
