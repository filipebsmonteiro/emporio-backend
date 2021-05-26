<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Models\Loja;
use Illuminate\Http\Request;

class LojaController extends ApiController
{
    public function __construct(Loja $loja)
    {
        parent::__construct($loja);
    }

    public function store(Request $request)
    {
        if ( auth('api_painel')->user()->getAuthIdentifier() > 1){
            throw new \HttpResponseException('Operação não autorizada, peça a um lojista!');
        }

        return parent::create($request);
    }

    public function update(Request $request, $id)
    {
        return parent::edit($request, $id);
    }
}
