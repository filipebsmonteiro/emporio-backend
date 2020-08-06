<?php


namespace App\Http\Controllers\API\Painel;


use App\Events\pedidoEvent;
use App\Http\Controllers\API\ApiController;
use App\Http\Resources\API\Pedido as PedidoResource;
use App\Http\Resources\API\PedidoCollection;
use App\Models\Fidelidade;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PedidoController extends ApiController
{
    public function __construct(Pedido $pedido)
    {
        parent::__construct($pedido);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->filterByLoja('api_painel');
        $this->Query = $this->Query->orderBy('created_at', 'desc');
        $this->executeQuery($request);
        return new PedidoCollection($this->Results);
    }

    public function show($id)
    {
        $entity = $this->Model->where('referencia', $id)->first();
        return response()->json(new PedidoResource($entity));
    }

    public function update(Request $request, $id)
    {
        $pedido = $this->Model->where('referencia', $id)->first();

        switch ($request->status) {
            case "Em Fabricação":
                if ($pedido->status != "Em Fabricação") {
                    $pedido->update([
                        'status' => $request->status,
                        'sent_at' => null,
                        'finalized_at' => null
                    ]);
                } else {
                    $pedido->update([
                        'status' => $request->status,
                        'confirmed_at' => Carbon::now()
                    ]);
                }
                break;
            case "Enviado":
                if ($pedido->agendamento) {
                    $agendamento = Carbon::createFromFormat('Y-m-d H:i:s', $pedido->agendamento)->format('Y-m-d');
                    $hoje = Carbon::now()->format('Y-m-d');
                    if ($pedido->agendamento && $agendamento != $hoje) {
                        throw new BadRequestHttpException('Esse Pedido não está com agendamento de entrega para hoje!');
                    }
                }
                $pedido->update([
                    'status' => $request->status,
                    'sent_at' => Carbon::now()
                ]);
                break;
            case "Cancelado":
                if ($pedido->resgate && $pedido->resgate->valorResgate) {
                    $pedido->resgate->delete();
                }
                $pedido->update([
                    'status' => $request->status,
                    'finalized_at' => Carbon::now()
                ]);
                break;
            case "Concluido":
                // Verifica se outro atendente não finalizou pedido
                // para não acumular outro fidelidade e mandar outro email
                if ($pedido->status != 'Concluido') {

                    $pedido->update([
                        'status' => $request->status,
                        'finalized_at' => Carbon::now()
                    ]);

                    $fidelidadeObj = new Fidelidade();
                    $fidelidade = $fidelidadeObj->where('Pedidos_idPedidos', $pedido->id)->first();
                    if ($fidelidade) {
                        // Calcula o valor pago menos o valor resgatado
                        $fidelidadeValor = ($pedido->valor + $pedido->taxaEntrega) - $fidelidade->valorResgate;
                        $fidelidadeValor = $fidelidadeObj->getPorcentagem() * $fidelidadeValor;
                    } else {
                        $fidelidadeValor = $fidelidadeObj->getPorcentagem() * ($pedido->valor + $pedido->taxaEntrega);
                    }

                    // Calcula o valor Total menos o produto descontado
                    if (!$pedido->cupons->isEmpty()) {

                        foreach ($pedido->cupons as $cupom) {
                            if (
                                $cupom->codigo == 'D' ||
                                $cupom->codigo == 'E' ||
                                $cupom->codigo == 'J' ||
                                $cupom->codigo == 'K'
                            ) {
                                $fidelidadeValor = $pedido->valor - $cupom->pivot->valor;
                            }
                        }
                    }

                    // Armazena o acumulado da fidelidade
                    $fidelidadeObj->create([
                        'valorAcumulado' => $fidelidadeValor,
                        'Clientes_idClientes' => $pedido->Clientes_idClientes,
                        'Lojas_idLojas' => $pedido->Lojas_idLojas
                    ]);

                    //Envia Email de Avaliação
                    //Mail::to($pedido->cliente->email)->send(new RatingMail($pedido));
                }

                break;
        }

        // Notifica Cliente e Atendente que esteja com pedido aberto
        $evtPedido = new pedidoEvent($pedido, "pedido-update-$id");
        event($evtPedido);
        return new PedidoResource($pedido);
    }
}
