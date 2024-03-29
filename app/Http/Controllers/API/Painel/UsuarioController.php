<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\ClienteFormRequest;
use App\Http\Requests\API\PainelUsuarioFormRequest;
use App\Models\Cliente;
use App\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Parent_;

class UsuarioController extends ApiController
{
    public function __construct(User $user)
    {
        parent::__construct($user);
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

    public function store(PainelUsuarioFormRequest $request)
    {
        $dataForm = $request->all();

        if ( !isset($dataForm['Lojas_idLojas']) ) {
            $dataForm['Lojas_idLojas']	= auth('api_painel')->user()->Lojas_idLojas;
        }

        if ( isset($dataForm['password']) ) {
            $dataForm['password']	= bcrypt($dataForm['password']);;
        }

        $usuario = $this->Model->create($dataForm);

        if ( isset($dataForm['perfil']) ) {
            $usuario->perfils()->sync($dataForm['perfil']);
        }

        return response()->json($usuario);
    }

    public function show($id)
    {
        $entity = $this->Model->with('perfils')->find($id);
        return response()->json($entity);
    }

    public function update(PainelUsuarioFormRequest $request, $id)
    {
        $dataForm = $request->all();

        if ( auth('api_painel')->user()->getAuthIdentifier() > 1 && $id === 1 ){
            throw new \HttpResponseException('Operação não autorizada!');
        }

        if ( isset($dataForm['password']) ) {
            $dataForm['password']	= bcrypt($dataForm['password']);;
        }else {
            unset($dataForm['password']);
        }

        $usuario = $this->Model->find($id);
        if ( isset($dataForm['perfil']) ) {
            $usuario->perfils()->sync($dataForm['perfil']);
            unset($dataForm['perfil']);
        }

        $usuario->update($dataForm);
        $usuario = $this->Model->find($id);

        return response()->json($usuario);
    }
}
