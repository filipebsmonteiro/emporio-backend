<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    public function perfils()
    {
        return $this->belongsToMany(Perfil::class, 'perfil_has_permissaos', 'Permissaos_idPermissaos', 'Perfils_idPerfils');
    }
}
