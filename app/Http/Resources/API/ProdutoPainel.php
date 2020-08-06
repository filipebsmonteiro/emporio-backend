<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoPainel extends JsonResource
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
            'imagem'                => $this->imagem,
            'unidade_medida'        => $this->unidade_medida,
            'minimo_unidade'        => $this->minimo_unidade,
            'intervalo'             => $this->intervalo,
            'promocionar'           => $this->promocionar,
            'valorPromocao'         => $this->valorPromocao,
            'categoria'             => new Cat_produto( $this->categoria ),
            'multiplos'             => new Ingrediente_multiploCollection( $this->multiplos ),
            //Abaixo usado no CRUD acima no pedido
            'status'                => $this->status,
            'Cat_produtos_idCat_produtos' => $this->Cat_produtos_idCat_produtos,
            'domingo'               => $this->domingo,
            'segunda'               => $this->segunda,
            'terca'                 => $this->terca,
            'quarta'                => $this->quarta,
            'quinta'                => $this->quinta,
            'sexta'                 => $this->sexta,
            'sabado'                => $this->sabado,
            'disponibilidade'       => $this->disponibilidade,
            'inicio_periodo1'       => $this->inicio_periodo1,
            'termino_periodo1'      => $this->termino_periodo1,
            'inicio_periodo2'       => $this->inicio_periodo2,
            'termino_periodo2'      => $this->termino_periodo2,
            'ingredientes'          => new IngredienteCollection( $this->ingredientes ),
            'combinacoes'           => new ProdMultiploPainelCollection( $this->produtosMultiplos ),
        ];
    }
}
