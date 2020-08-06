<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Models\Fidelidade;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FidelidadeController extends ApiController
{
    public function __construct(Fidelidade $fidelidade)
    {
        parent::__construct($fidelidade);
    }

    public function store(Request $request)
    {
        if ($request->valorAcumulado && $request->valorResgate){
            throw new BadRequestHttpException('Não se pode Acumular e Resgatar ao mesmo tempo');
        }

        $data = [
            'Clientes_idClientes' => $request->Clientes_idClientes,
            'Lojas_idLojas' => 1
        ];

        if ($request->valorAcumulado) {
            $data['valorAcumulado'] = $request->valorAcumulado;
        }

        if ($request->valorResgate){
            $data['valorResgate'] = $request->valorResgate;
        }

        $fideldade = $this->Model->create($data);
        return response()->json($fideldade);
    }
}
