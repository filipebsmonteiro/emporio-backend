<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente extends Model
{
    use SoftDeletes;

	protected $fillable = [
        'nome',
        'status',
        'preco',
        'codigo_PDV',
        'Lojas_idLojas',
        'Cat_ingredientes_idCat_ingredientes'
	];

    /**
     * Utilizado no arquivo:
     * App\Http\Resources\API\Ingrediente
     */
    public function nesseMultiplo() {
        return $this->hasOne(Ingrediente_ing_multiplo::class, 'Ingredientes_idIngredientes', 'id');
    }

	public function categoria() {
		return $this->belongsTo(Categoria_ingrediente::class, 'Cat_ingredientes_idCat_ingredientes', 'id');
	}

	public function produtos() {
        return $this->belongsToMany(Produto::class, 'ingrediente_produtos', 'Ingredientes_idIngredientes', 'Produtos_idProdutos')
            ->withPivot('visibilidade');
    }

    public function multiplos() {
        return $this->belongsToMany(Ingrediente_multiplo::class, 'ingrediente_ing_multiplos', 'Ingredientes_idIngredientes', 'Multiplos_idMultiplos')
            ->withPivot('id', 'valor')
            ->orderBy('nome');
    }
}
