<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ped_prod_ing_multiplo extends Model
{
    protected $fillable = [
        'quantidade',
        'valor',
        'Ped_produtos_idPed_produtos',
        'Multiplos_idMultiplos',
        'Ingredientes_idIngredientes',
    ];

    public function multiplo () {
        return $this->hasOne(Ingrediente_multiplo::class, 'id', 'Multiplos_idMultiplos');
    }

    public function ingrediente () {
        return $this->hasOne(Ingrediente::class, 'id', 'Ingredientes_idIngredientes');
    }
}
