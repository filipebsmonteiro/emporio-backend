<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Models\FormaPagamento;

class FormaPagamentoController extends ApiController
{
    public function __construct(FormaPagamento $cupom)
    {
        parent::__construct($cupom);
    }
}
