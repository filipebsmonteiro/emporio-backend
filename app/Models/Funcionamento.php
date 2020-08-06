<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionamento extends Model
{
    protected $fillable = [
        'dia_semana',
        'inicio_funcionamento',
        'termino_funcionamento',
        'inicio_delivery',
        'termino_delivery',
        'Lojas_idLojas'
    ];
}
