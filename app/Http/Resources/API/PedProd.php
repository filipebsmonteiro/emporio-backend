<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedProd extends JsonResource
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
            'quantidade'        => $this->quantidade,
            'valor'             => $this->valor,
            'nome'              => $this->produto->nome,
            'imagem'            => $this->produto->imagem,
            'unidade_medida'    => $this->produto->unidade_medida,
            'multiplos'         => new Ing_mult_prod_pedidoCollection( $this->multiplos ),
            'combinacoes'       => new CombinacaoCollection( $this->combinacoes ),
        ];
    }
}
