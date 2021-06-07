<?php

namespace App\Http\Resources\API;

use App\Models\Pedidos_produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Parent_;

class Pedido extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status'            => $this->status,
            'referencia'        => $this->referencia,
            'observacoes'       => $this->observacoes,
            'valor'             => $this->valor,
            'taxa_entrega'      => $this->taxa_entrega,
            'valor_fidelidade'  => $this->valor_fidelidade,
            'confirmed_at'      => $this->confirmed_at,
            'sent_at'           => $this->sent_at,
            'finalized_at'      => $this->finalized_at,
            'agendamento'       => $this->agendamento,
            'cliente'           => new Cliente( $this->cliente ),
            'endereco'          => $this->enderecoEntrega,
            'forma_pagamento'   => $this->formaPagamento->first(),
            'produtos'          => new PedProdCollection( $this->pedProds ),
            'resgate'           => $this->resgate ? $this->resgate->valorResgate : null,
            //'pagamentos_online' => new PagamentoCollection( $this->pagamentosOnline ),
            'entrega'           => $this->enderecoEntrega,
            'cupons'            => $this->cupons,
            'created_at'        => $this->created_at,
        ];
    }
}
