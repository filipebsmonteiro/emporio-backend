<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil_has_permissao extends Model
{
    protected $fillable = [
        'Perfils_idPerfils',
        'Permissaos_idPermissaos'
    ];
}
