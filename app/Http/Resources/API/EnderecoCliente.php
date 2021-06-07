<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnderecoCliente extends JsonResource
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
            'CEP'           => $this->CEP,
            'Logradouro'    => $this->Logradouro,
            'Bairro'        => $this->Bairro,
            'Cidade'        => $this->Cidade,
            'Referencia'    => $this->Referencia,
            'taxa_entrega'  => $this->cep()->taxa_entrega,
        ];
    }
}
