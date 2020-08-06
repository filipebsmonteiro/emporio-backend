<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ing_multiplos_produto extends Model
{
    use SoftDeletes;

    public $name = 'ing_multiplos_produtos';

    protected $fillable = [
    	'Multiplos_idMultiplos',
    	'Produtos_idProdutos'
    ];
}
