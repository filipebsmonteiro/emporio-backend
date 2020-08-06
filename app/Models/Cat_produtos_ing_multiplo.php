<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cat_produtos_ing_multiplo extends Model
{
    use SoftDeletes;

	protected $fillable = [
			'Cat_produtos_idCat_produtos',
            'Multiplos_idMultiplos',
			'visibilidade'
	];
	
	public $name		= 'cat_produtos_ing_multiplos';
}
