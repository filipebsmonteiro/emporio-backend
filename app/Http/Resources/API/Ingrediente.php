<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Ingrediente extends JsonResource
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
            'id'            => $this->id,
            'nome'          => $this->nome,
            'status'        => $this->status,
            'preco'         => $this->preco,
            'nesseMultiplo' => $this->nesseMultiplo ? $this->nesseMultiplo->valor : null, // Usada no carrinho
            'visibilidade'  => $this->pivot ? $this->pivot->visibilidade : null, // Usada no Painel CRUD
        ];
    }
}
