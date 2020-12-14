<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loja extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fantasia',
        'CNPJ',
        'razao_social',
        'abreviacao',
        'phone',
        'CEP',
        'Logradouro',
        'Bairro',
        'Cidade',
        'latitude',
        'longitude',
        'vlr_minimo_pedido',
        'agendamentos',
        'pagamentosOnline',
        'CieloMerchantID',
        'CieloMerchantKey'
    ];

    public function isOpen()
    {
        if (
            Carbon::now()->toTimeString() > $this->funcionamento()->inicio_delivery
            && Carbon::now()->toTimeString() < $this->funcionamento()->termino_delivery
        ){
            return true;
        }

        return false;
    }

    public function hasAtendentesOnline()
    {
        $userObj    = new User();
        $Users      = $userObj
                    ->where('status', true)
                    ->where('Lojas_idLojas', $this->id)
                    ->get();

        if ( $Users->isNotEmpty() ){
            foreach ($Users as $key => $user) {
                if ($user->hasAnyPerfils('Atendente')) {
                    return true;
                }
            }
        }

        return false;
    }

    public function ceps()
    {
        return $this->belongsToMany(
            Ceps::class,
            'ceps_lojas',
            'Lojas_idLojas',
            'Ceps_idCeps'
        );
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'Lojas_idLojas', 'id');
    }

    public function funcionamentos()
    {
        return $this->hasMany(Funcionamento::class, 'Lojas_idLojas', 'id');
    }

//    Caso não informe o dia, reotrna funcionamento de hoje
    public function funcionamento($diaSemana='')
    {
        if ( $diaSemana == '' ) {
            // Determina o dia da Semana
            switch ( Carbon::today()->dayOfWeek ) {
                case 0:
                    $diaSemana = 'domingo';
                    break;
                case 1:
                    $diaSemana = 'segunda';
                    break;
                case 2:
                    $diaSemana = 'terca';
                    break;
                case 3:
                    $diaSemana = 'quarta';
                    break;
                case 4:
                    $diaSemana = 'quinta';
                    break;
                case 5:
                    $diaSemana = 'sexta';
                    break;
                case 6:
                    $diaSemana = 'sabado';
                    break;
            }
        }

        return $this->funcionamentos->where('dia_semana', $diaSemana)->first();
    }
}
