<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combos_prod_multiplo extends Model
{
    use SoftDeletes;

    public $name    = 'combos_prod_multiplos';

    protected $fillable = [
        'Produtos_idProdutos',
        'Prod_Multiplos_idProd_Multiplos'
    ];
}
