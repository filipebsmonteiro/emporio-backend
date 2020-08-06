<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Ing_mult_prod_pedido extends JsonResource
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
            'quantidade'    => $this->quantidade,
            'valor'         => $this->valor,
            'multiplo'      => $this->multiplo,
            'ingrediente'   => $this->ingrediente,
        ];
    }
}
