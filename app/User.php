<?php

namespace App;

use App\Models\Loja;
use App\Models\Perfil;
use App\Models\Permissao;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'Lojas_idLojas'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    //Retorna os Perfis que Possuem aquelaPermissao
    public function perfils()
    {
        return $this->belongsToMany(Perfil::class, 'user_has_perfils', 'Users_idUsers', 'Perfils_idPerfils');
    }

    public  function hasPermissao(Permissao $permissao)
    {
        //dd($permissao->perfils());
        // Retorna os perfis que Possuem a permissao especifica
        return $this->hasAnyPerfils($permissao->perfils());
    }

    public function hasAnyPerfils($perfils)
    {

        $perfisUser			= $this->perfils()->get();
        if ( is_array($perfils) || is_object($perfils) ) {

            $perfisAutorizados	= $perfils->get();
            //dd($perfisAutorizados->intersect($perfisUser)->count());
            return !! $perfisAutorizados->intersect($perfisUser)->count();

        }

        return $this->perfils()->get()->contains('nome', $perfils);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'Lojas_idLojas','id');
    }
}
