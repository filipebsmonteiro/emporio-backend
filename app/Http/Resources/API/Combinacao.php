<?php

namespace App\Http\Resources\API;

use App\Models\Produto_multiplo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Combinacao extends JsonResource
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
            'preco'                 => $this->pivot->valor,

            // Layout Combo
            'produto_multiplo'         => $this->whenPivotLoaded('ped_prod_produtos', function () {
                $prodMult= new Produto_multiplo();
                return $prodMult->find($this->pivot->Ped_prod_mult_idPed_prod_mult);
            }),
        ];
    }
}
