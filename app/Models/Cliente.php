<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Cliente extends Authenticatable implements JWTSubject
{
    use Notifiable;// Authorizable, CanResetPassword, SoftDeletes

    protected $fillable = [
        'nome',
        'CPF',
        'phone',
        'email',
        'password',
        'nascimento',
        'sexo',
        'facebook_id',
        'newsletter'
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function pedidos()
    {
    	return $this->hasMany(Pedido::class, 'Clientes_idClientes', 'id');
    }

    public function enderecos()
    {
    	return $this->hasMany(EnderecoCliente::class, 'Clientes_idClientes', 'id');
    }

    public function roteiros()
    {
        return $this->belongsToMany(
            Roteiro::class,
            'cliente_roteiros',
            'Clientes_idClientes',
            'Roteiros_idRoteiros'
        );
    }
}
