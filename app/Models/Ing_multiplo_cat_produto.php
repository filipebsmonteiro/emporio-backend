<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ing_multiplo_cat_produto extends Model
{
    public $name    = 'ing_multiplo_cat_produtos';

    protected $fillable = [
    		'Cat_produtos_idCat_produtos',
    		'Multiplos_idMultiplos'
    ];
}
