<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ped_prod_produto extends Model
{
    /*
     * TABELA DE COMBINAÇÔES (Layout Pizza)
     */
    protected $fillable = [
        'quantidade',
        'valor',
        'Ped_prod_mult_idPed_prod_mult',    // Usada Somente para Subprodutos no Layout Combo
        'Ped_produtos_idPed_produtos',      // Usada para frações produtos no Layout Pizza e Subprodutos no Layout Combo
        'Produtos_idProdutos'
    ];

    public function multiplo()
    {
        return $this->hasOne(Produto_multiplo::class, 'id', 'Ped_prod_mult_idPed_prod_mult');
    }
}
