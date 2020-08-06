<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedidos_produto extends Model
{
    protected $fillable = [
        'quantidade',
        'valor',
        'Pedidos_idPedidos',
        'Produtos_idProdutos'
    ];

    public function produto()
    {
        return $this->hasOne(Produto::class, 'id', 'Produtos_idProdutos');
    }

    public function multiplos (){
        return $this->hasMany(Ped_prod_ing_multiplo::class, 'Ped_produtos_idPed_produtos', 'id');
    }
    /**
     * Utilizado no Arquivo: App\Http\Controllers\API\Site\PedidoController
     */
    public function multiplo()
    {
        return
            $this->belongsToMany(Produto::class, 'ped_prod_ing_multiplos', 'Ped_produtos_idPed_produtos', 'Multiplos_idMultiplos')
                ->withPivot('quantidade', 'valor');
    }

    public function ingrediente()
    {
        return
            $this->belongsToMany(Ingrediente::class, 'ped_prod_ing_multiplos', 'Ped_produtos_idPed_produtos', 'Ingredientes_idIngredientes')
                ->withPivot('quantidade', 'valor');
    }
    /*
     * FIM Lista Subingredientes
     */

    /*
     * Lista Combinacoes (Layout Pizza)
     * Lista Suprodutos (Layout Combo)
     */
    public function combinacoes ()
    {
        return
            $this->belongsToMany(Produto::class, 'ped_prod_produtos', 'Ped_produtos_idPed_produtos', 'Produtos_idProdutos')
                ->withPivot('id', 'quantidade', 'valor');
    }

    /*** BLOCO COMBO ***/
    /*
     * Lista Relacionamentos dos Subprodutos (Layout Combo)
     * /
    public function pedProdMultiplo()
    {
        return $this->hasMany(Ped_prod_prod_multiplo::class, 'Ped_produtos_idPed_produtos', 'id');
    }

    /*
     * Lista Subprodutos (Layout Combo)
     * /
    public function subProdutos ()
    {
        return
            $this->belongsToMany(Produto::class, 'ped_prod_prod_multiplos', 'Ped_produtos_idPed_produtos', 'Produtos_idProdutos')
                ->withPivot('id', 'quantidade', 'valor');
    }*/

}
