<?php


namespace App\Http\Controllers\API\Site;


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

    public function index(Request $request)
    {
        $id             = auth('api')->user()->getAuthIdentifier();
        $data           = Carbon::now()->subMonth(12);
        $acumulados     = $this->acumuladosCliente($id);
        $resgates       = $this->restagtesCliente($id);
        $porcentagem    = $this->Model->getPorcentagem();
        return response()->json([
            'data'          => $data,
            'acumulados'    => $acumulados,
            'resgates'      => $resgates,
            'porcentagem'   => $porcentagem,
        ]);
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

    /**
     * Retorna Resgates feitos no ultimo ano pelo Cliente Informado
     *
     * @param $idCliente
     * @return mixed
     */
    public function restagtesCliente($idCliente)
    {
        $resgates   = $this->Model
            ->where('fidelidades.created_at', '>', Carbon::now()->subMonth(12)->toDateString())
            ->where('fidelidades.Clientes_idClientes', $idCliente)
            ->whereNotNull('valorResgate')
            ->get();

        return $resgates;
    }

    public function acumuladosCliente($idCliente)
    {
        $acumulados     = $this->Model
            ->where('created_at', '>', Carbon::now()->subMonth(12)->toDateString())
            ->where('Clientes_idClientes', $idCliente)
            ->whereNotNull('valorAcumulado')
            ->get();
        return $acumulados;
    }

    /**
     * Retorna valor para Resgate que o Cliente informado possui
     * Baseado no Ultimo Ano
     *
     * @param $idCliente
     * @return mixed
     */
    public function valorResgate($idCliente): float
    {
        $acumulados     = $this->acumuladosCliente($idCliente);
        $resgates       = $this->restagtesCliente($idCliente);
        return ($acumulados->sum('valorAcumulado') - ($resgates->sum('valorResgate')));
    }
}
