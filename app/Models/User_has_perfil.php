<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_has_perfil extends Model
{
    protected $fillable = [
        'Users_idUsers',
        'Perfils_idPerfils'
    ];
}
