<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnderecoCliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'CEP',
        'Logradouro',
        'Bairro',
        'Cidade',
        'Referencia',
        'Clientes_idClientes'
    ];

    public function cep(){
        $cepString      = str_replace(str_split('.-'),"", $this->CEP);
        $CepObj         = new Ceps();
        $cep            = $CepObj->where('CEP', $cepString)->first();
        return          $cep;
    }

    public function lojaResponsavel(){
        $cepString      = str_replace(str_split('.-'),"", $this->CEP);
        $CepObj         = new Ceps();
        $cep            = $CepObj->where('CEP', $cepString)->first();

        $lojaCepObj     = new Ceps_loja();
        $lojaCep        = $lojaCepObj->where('Ceps_idCeps', $cep->id)->first();

        $lojaObj        = new Loja();
        $loja           = $lojaObj->find($lojaCep->Lojas_idLojas);

        return          $loja;
    }
}
