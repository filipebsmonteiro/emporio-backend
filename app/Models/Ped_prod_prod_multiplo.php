<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ped_prod_prod_multiplo extends Model
{
    protected $fillable = [
        'quantidade',
        'valor',
        'Ped_produtos_idPed_produtos',
        'Prod_Multiplos_idProd_Multiplos',  // ID Produto multiplo
        'Produtos_idProdutos',              // ID do Subproduto
    ];

    /*
     * Produto Multiplo escolhido
     */
    public function ProdutoMultiplo(){
        return $this->hasOne(Produto_multiplo::class, 'id', 'Prod_Multiplos_idProd_Multiplos');
    }

    /*
     * Subproduto escolhido
     */
    public function subProduto(){
        return $this->hasOne(Produto::class, 'id', 'Produtos_idProdutos');
    }

    /*
     * Lista Subingredientes do Subproduto
     */
    public function multiplos(){
        return $this->hasMany(Ped_prod_ing_multiplo::class, 'Ped_prod_mult_idPed_prod_mult', 'id');
    }

    /*
     * Combinaçoes do Subproduto (Layout Pizza)
     * Tablela utilizada no Layout Pizza SIMPLES Também
     */
    public function combinacoes(){
        return
            $this->belongsToMany(Produto::class, 'ped_prod_produtos', 'Ped_prod_mult_idPed_prod_mult', 'Produtos_idProdutos')
                ->withPivot('id', 'quantidade', 'valor');
    }

}
