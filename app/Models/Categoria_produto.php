<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria_produto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'grupo',
        'layout',
        'permiteCombinacao',
        'quantidadeCombinacoes',
        'Lojas_idLojas'
    ];
    
    public $tipoCategoria = [
        'Padrão',
        'Pizza',
        'Combo'
    ];

    public function produtos() {
        return $this->hasMany(Produto::class, 'Cat_produtos_idCat_produtos','id');
    }

    public function multiplos() {
        return $this->belongsToMany(
            Ingrediente_multiplo::class,
            'cat_produtos_ing_multiplos',
            'Cat_produtos_idCat_produtos',
            'Multiplos_idMultiplos'
        )->wherePivot('deleted_at', null)
        ->orderBy('id');
    }
    
}
