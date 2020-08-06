<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Models\Loja;

class LojaController extends ApiController
{
    public function __construct(Loja $loja)
    {
        parent::__construct($loja);
    }
}
