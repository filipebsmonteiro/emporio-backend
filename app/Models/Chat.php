<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /**
     * Variavel Channel será :
     * Clientes_idClientes.( Carbon::now()->dayOfYear . Carbon::now()->year )
     */
    protected $fillable = [
        'channel',
        'Clientes_idClientes',
        'Users_idUsers',
        'mensagens'
    ];

    public function cliente(){
        return $this->hasOne(Cliente::class, 'id', 'Clientes_idClientes');
    }
}
