<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Produto extends JsonResource
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
            'preco'                 => $this->preco,
            'status'                => $this->status,
            'imagem'                => $this->imagem,
            'unidade_medida'        => $this->unidade_medida,
            'minimo_unidade'        => $this->minimo_unidade,
            'intervalo'             => $this->intervalo,
            'promocionar'           => $this->promocionar,
            'valorPromocao'         => $this->valorPromocao,
            'categoria'             => new Cat_produto( $this->categoria ),
            'multiplos'             => new Ingrediente_multiploCollection( $this->multiplos ),
            'ingredientes'          => display_ingredientes($this),
            'combinacoes'           => new ProdMultiploCollection( $this->produtosMultiplos ),
        ];
    }
}
