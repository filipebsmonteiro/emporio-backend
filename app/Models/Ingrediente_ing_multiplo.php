<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente_ing_multiplo extends Model
{
    use SoftDeletes;

	protected $fillable = [
			'Ingredientes_idIngredientes',
			'Multiplos_idMultiplos',
			'valor'
	];
}
