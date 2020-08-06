<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdMultiploPainel extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'nome'              => $this->nome,
            'obrigatorio'       => $this->obrigatorio,
            'quantidade_min'    => $this->quantidade_min,
            'quantidade_max'    => $this->quantidade_max,
            'produtos'          => new SubProdutoCollection( $this->produtos ),
        ];
    }
}
