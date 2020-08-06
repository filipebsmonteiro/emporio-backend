<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Cliente extends JsonResource
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
            'nome'          => $this->nome,
            'CPF'           => $this->CPF,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'nascimento'    => $this->nascimento,
            'sexo'          => $this->sexo,
        ];
    }
}
