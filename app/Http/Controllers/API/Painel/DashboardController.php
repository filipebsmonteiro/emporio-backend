<?php


namespace App\Http\Controllers\API\Painel;


use App\Models\Pedido;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController
{
    const DAY = 'day';
    const WEEK = 'week';
    const WEEKDAY = 'week_day';

    private $inicio;
    private $termino;
    private $pedidos;
    private $arrayDay = array();
    private $arrayByWeek = array();
    private $arrayDayOfWeek = array();

    public function __construct()
    {
        $this->inicio = Carbon::today()->startOfWeek();
        $this->termino = $this->inicio->copy()->addDays(6);
    }

    public function index(Request $request)
    {
        if ($request->data_inicio && $request->data_termino) {
            $this->inicio = Carbon::createFromFormat('Y-m-d H:i', $request->data_inicio);
            $this->termino = Carbon::createFromFormat('Y-m-d H:i', $request->data_termino);
        }

        $this->loadPedidos();

        $type = self::WEEKDAY;
        $array_retorno = array();
        if ($request->group_by) {
            $type = $request->group_by;
        }

        if ($type === self::DAY) {
            $array_retorno = $this->countByDay();
        }
        if ($type === self::WEEKDAY) {
            $array_retorno = $this->countByDayOfWeek();
        }
        if ($type === self::WEEK) {
            $array_retorno = $this->countByWeeks();
        }

        return response()->json($array_retorno);
    }

    protected function loadPedidos(): void
    {
        $pedidoObj = new Pedido();
        $builder = $pedidoObj->newQuery()
            ->whereDate('created_at', '>=', $this->inicio)
            ->whereDate('created_at', '<=', $this->termino)
            ->where('status', '!=', 'Cancelado')
            ->where('Clientes_idClientes', '>', 1);

        if (auth('api_painel')->user()->getAuthIdentifier() > 1) {
            $builder = $builder->where('Lojas_idLojas', '=', auth('api_painel')->user()->loja->id);
        }

        $this->pedidos = $builder->orderBy('created_at')->get();
    }

    public function countByDay(): array
    {
        $this->groupByDay();
        $array_count = array();
        foreach ($this->arrayDay as $dia => $pedidos) {
            $array_count[$dia] = $pedidos->count();
        }
        return $array_count;
    }

    protected function groupByDay(): void
    {
        $this->arrayDay = $this->pedidos->groupBy(function ($item) {
            return Carbon::createFromFormat('Y-m-d', $item->created_at);
        });
    }

    public function countByDayOfWeek(): array
    {
        $this->groupByDayOfWeek();
        $array_count = array();
        $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
        foreach ($diasSemana as $index => $dia_ptBr) {
            $quantidade_pedidos = 0;
            if (isset($this->arrayDayOfWeek[$index])) {
                $quantidade_pedidos = $this->arrayDayOfWeek[$index]->count();
            }

            $array_count[$dia_ptBr] = $quantidade_pedidos;
        }
        return $array_count;
    }

    protected function groupByDayOfWeek(): void
    {
        $this->arrayDayOfWeek = $this->pedidos->groupBy(function ($item) {
            // $dayOfWeek 0 (for Sunday) through 6 (for Saturday)
            return Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->dayOfWeek;
        });
    }

    public function countByWeeks(): array
    {
        $this->groupByWeek();
        $array_count = array();
        foreach ($this->arrayByWeek as $monday => $pedidosDaquelaSemana) {
            $array_count[$monday] = $pedidosDaquelaSemana->count();
        }

        return $array_count;
    }

    protected function groupByWeek(): void
    {
        $this->arrayByWeek = $this->pedidos->groupBy(function ($item) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)
                ->startOfWeek()
                ->subDay()// Carbon week Starts on Monday, Subtract a Day, to Equalize with MomentJS
                ->format('d/m/y');
        });
    }

    public function cardsInfo(Request $request)
    {
        if ($request->data_inicio && $request->data_termino) {
            $this->inicio = Carbon::createFromFormat('Y-m-d H:i', $request->data_inicio);
            $this->termino = Carbon::createFromFormat('Y-m-d H:i', $request->data_termino);
        }

        $this->loadAllPedidos();
        $abertos = $this->pedidos->filter(function ($item) {
            return $item->status == 'Pedido Realizado';
        });
        $confirmados = $this->pedidos->filter(function ($item) {
            return $item->status == 'Em Fabricação';
        });
        $enviados = $this->pedidos->filter(function ($item) {
            return $item->status == 'Enviado';
        });
        $cancelados = $this->pedidos->filter(function ($item) {
            return $item->status == 'Cancelado';
        });
        $validos = $this->pedidos->filter(function ($item) {
            return $item->status != 'Cancelado';
        });

        $array_cards = [
            'abertos' => $abertos->count(),
            'confirmados' => $confirmados->count(),
            'enviados' => $enviados->count(),
            'cancelados' => $cancelados->count(),
            'faturamento' => $validos->sum('valor') + $validos->sum('taxaEntrega'),
            'media_valor' => $validos->avg('valor'),
            'media_entrega' => $validos->avg('taxaEntrega'),
        ];

        return response()->json($array_cards);
    }

    protected function loadAllPedidos(): void
    {
        $pedidoObj = new Pedido();
        $builder = $pedidoObj->newQuery()
            ->whereDate('created_at', '>=', $this->inicio)
            ->whereDate('created_at', '<=', $this->termino)
            ->where('Clientes_idClientes', '>', 1);

        if (auth('api_painel')->user()->getAuthIdentifier() > 1) {
            $builder = $builder->where('Lojas_idLojas', '=', auth('api_painel')->user()->loja->id);
        }

        $this->pedidos = $builder->orderBy('created_at')->get();
    }
}
