<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\ClienteFormRequest;
use App\Models\Cliente;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Parent_;

class ClienteController extends ApiController
{
    public function __construct(Cliente $cliente)
    {
        parent::__construct($cliente);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        if (auth('api_painel')->user()->getAuthIdentifier() > 1) {
            $this->Query = $this->Query->where('id', '>', 1);
        }
        $this->executeQuery($request);
        return response()->json($this->Results);
    }

    public function update(Request $request, $id)
    {
        return parent::edit($request, $id);
    }
}
