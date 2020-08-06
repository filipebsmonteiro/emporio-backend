<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente_produto extends Model
{
    use SoftDeletes;

    public $name = 'ingrediente_produtos';

    protected $fillable = [
        'visibilidade',
        'Ingredientes_idIngredientes',
        'Produtos_idProdutos'
	];
}
