<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente_multiplo extends Model
{
    use SoftDeletes;

	protected $fillable = [
        'nome',
        'obrigatorio',
        'visibilidade',
        'quantidade_min',
        'quantidade_max',
        'Lojas_idLojas'
	];

    public function produto() {
        return $this->belongsToMany(Produto::class, 'ing_multiplos_produtos', 'Multiplos_idMultiplos', 'Produtos_idProdutos');
    }

    public function ingredientes() {
        return $this->belongsToMany(Ingrediente::class, 'ingrediente_ing_multiplos', 'Multiplos_idMultiplos', 'Ingredientes_idIngredientes')
            ->where('status', true) //Trata dos Ingredientes indisponiveis
            ->withPivot('id', 'valor')
            ->orderBy('nome');
    }

    protected $visibilidades = [
        'Ingrediente', 'Essencial Visível', 'Essencial Não Visível'
    ];

    public function getVisibilidades(){
        return $this->visibilidades;
    }

    public function isMultipleChoice(){
        if($this->obrigatorio){
//            Valida 1
            if ( $this->quantidade_min == 1 ){
                if ($this->quantidade_max == 1)
                    return false;

                if ($this->quantidade_max > 1)
                    return true;
            }

//            Valida Maior que 1
            if ( $this->quantidade_min > 1 ){
                if ($this->quantidade_max > 1)
                    return true;
            }

        }else{
//            Valida 0
            if ( $this->quantidade_min == 0 ){
                if (
                    $this->quantidade_max == 0 ||
                    $this->quantidade_max == 1
                )
                    return false;

                if ($this->quantidade_max > 1)
                    return true;
            }

//            Valida 1
            if ( $this->quantidade_min == 1 ){
                if ($this->quantidade_max == 1)
                    return false;

                if ($this->quantidade_max > 1)
                    return true;
            }

//            Valida Maior que 1
            if ( $this->quantidade_min > 1 ){
                if ($this->quantidade_max > 1)
                    return true;
            }
        }

        return false;
    }
}
