<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\ClienteFormRequest;
use App\Models\Cliente;
use Illuminate\Database\QueryException;

class ClienteController extends ApiController
{
    public function __construct(Cliente $cliente)
    {
        parent::__construct($cliente);
    }

    public function store(ClienteFormRequest $request)
    {
        $dataForm = $request->all();
        $dataForm['password'] = bcrypt($dataForm['password']);
        $cliente = $this->Model->create($dataForm);

        return response()->json($cliente);
    }

    public function update(ClienteFormRequest $request, $id)
    {
        $dataForm = $request->all();

        if ( auth('api')->user()->getAuthIdentifier() != $id ){
            throw new \HttpResponseException('Operação não autorizada!');
        }

        if ( isset($dataForm['password']) ) {
            $dataForm['password']	= bcrypt($dataForm['password']);;
        }else {
            array_pull($dataForm, 'password');
        }

        $cliente = $this->Model->find($id);
        $cliente->update($dataForm);
        $cliente = $this->Model->find($id);

        return response()->json($cliente);
    }
}
