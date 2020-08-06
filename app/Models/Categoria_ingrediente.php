<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria_ingrediente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'Lojas_idLojas'
	];
	
	public function ingredientes() {
		return $this->hasMany(
		    Ingrediente::class,
            'Cat_ingredientes_idCat_ingredientes',
            'id'
        );
	}
}
