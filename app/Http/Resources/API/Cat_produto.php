<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Cat_produto extends JsonResource
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
            'id'                    => $this->id,
            'nome'                  => $this->nome,
            'grupo'                 => $this->grupo,
            'layout'                => $this->layout,
            'permiteCombinacao'     => $this->permiteCombinacao,
            'quantidadeCombinacoes' => $this->quantidadeCombinacoes,
            'multiplos'             => new Ingrediente_multiploCollection( $this->multiplos )
        ];
    }
}
