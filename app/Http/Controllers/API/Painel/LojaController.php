<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Models\Loja;

class LojaController extends ApiController
{
    public function __construct(Loja $loja)
    {
        parent::__construct($loja);
    }
}
