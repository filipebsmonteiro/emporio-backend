<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto_multiplo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'obrigatorio',
        'quantidade_min',
        'quantidade_max',
        'valor',
        'Lojas_idLojas'
    ];

    public function produtos() {

        return $this->belongsToMany(
            Produto::class,
            'prod_multiplo_produtos',
            'Prod_Multiplos_idProd_Multiplos',
            'Produtos_idProdutos'
        )->withPivot('id', 'valor');
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
