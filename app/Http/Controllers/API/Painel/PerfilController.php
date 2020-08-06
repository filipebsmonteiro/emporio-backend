<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends ApiController
{
    public function __construct(Perfil $perfil)
    {
        parent::__construct($perfil);
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
}
