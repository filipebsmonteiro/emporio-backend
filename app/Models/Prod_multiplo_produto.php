<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prod_multiplo_produto extends Model
{
    protected $fillable = [
        'Prod_Multiplos_idProd_Multiplos',
        'Produtos_idProdutos',
        'valor'
    ];
}
