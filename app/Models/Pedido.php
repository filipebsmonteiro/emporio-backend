<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status',
        'referencia',
        'observacoes',
        'valor',
        'taxaEntrega',
        'valorFidelidade',
        'Clientes_idClientes',
        'Enderecos_idEnderecos',
        'Lojas_idLojas',
        'ip_address',
        'confirmed_at',
        'sent_at',
        'finalized_at',
        'print',
        'agendamento'
    ];

    /**
     * Utilizado no arquivo: App\Http\Resources\API\Pedido
     */
    public function pedProds()
    {
        return $this->hasMany(Pedidos_produto::class, 'Pedidos_idPedidos', 'id');
    }

    public function produtos()
    {
        return
            $this->belongsToMany(Produto::class, 'pedidos_produtos', 'Pedidos_idPedidos', 'Produtos_idProdutos')
                ->withPivot('quantidade', 'valor');
    }

    public function resgate()
    {
        return $this->hasOne(Fidelidade::class, 'Pedidos_idPedidos', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Clientes_idClientes', 'id');
    }

    public function enderecoEntrega()
    {
        return $this->belongsTo(EnderecoCliente::class, 'Enderecos_idEnderecos', 'id');

    }

    public function formaPagamento()
    {
        return $this->belongsToMany(
            FormaPagamento::class, 'pedido_forma_pagamentos',
            'Pedidos_idPedidos', 'Formas_idFormas'
        )->withPivot('troco');
    }

    public function cupons()
    {
        return $this->belongsToMany(
            Cupom::class,
            'pedido_cupoms',
            'Pedidos_idPedidos',
            'Cupoms_idCupoms'
        )->withPivot('valor');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'Lojas_idLojas', 'id');
    }

    public function pagamentosOnline()
    {
        return $this->hasMany(Pagamento::class, 'Pedidos_idPedidos', 'id');
    }

    protected $status = ['Pedido Realizado', 'Cancelado', 'Em Fabricação', 'Enviado'];

    public function getStatus()
    {
        return $this->status;
    }
}
