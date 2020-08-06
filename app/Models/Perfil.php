<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $fillable = [
        'nome',
        'descricao'
    ];

    public function permissoes()
    {
        return $this->belongsToMany(
            Permissao::class,
            'perfil_has_permissaos',
            'Perfils_idPerfils',
            'Permissaos_idPermissaos',
            'id'
        );
    }

}
