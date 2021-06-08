<?php

namespace App\Http\Controllers\API\Painel;

use App\Http\Controllers\API\ApiController;
use App\Models\Ceps;
use App\Models\Ceps_loja;
use App\Models\Loja;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CEPController extends ApiController
{
    protected $table;
    protected $cepLojaTable;

    public function __construct(Ceps $ceps)
    {
        parent::__construct($ceps);
        $this->table = $ceps->getTable();
        $cepLoja = new Ceps_loja();
        $this->cepLojaTable = $cepLoja->getTable();
    }

    public function bairros()
    {
        return $this->Query->distinct('bairro')->pluck('bairro');
    }

    public function index(Request $request)
    {
        $request->filters = json_decode($request->filters);

        if (property_exists($request->filters, 'available')) {
            return $this->available($request->filters->bairro);
        }

        if (property_exists($request->filters, 'attached')) {
            return $this->attached($request->filters->bairro);
        }

        return response();
    }

    public function available($bairro)
    {
        // Lista Ceps que Ainda Não Possuem Lojas Entregando naquele bairro
        return $this->Query
            ->from($this->table, 'c')
            ->leftJoin($this->cepLojaTable . ' as cl', 'c.id', '=', 'cl.Ceps_idCeps')
            ->whereNull('cl.Ceps_idCeps')
            ->where('c.bairro', 'LIKE', $bairro)
            ->get([
                'c.id',
                'c.taxa_entrega',
                'c.vlr_minimo_pedido',
                'c.logradouro'
            ]);
    }

    public function attached($bairro)
    {
        // Lista Ceps que a Loja informada está Entregando naquele Bairro
        return $this->Query
            ->from($this->table, 'c')
            ->leftJoin($this->cepLojaTable . ' as cl', 'c.id', '=', 'cl.Ceps_idCeps')
            ->where('cl.Lojas_idLojas', auth('api_painel')->user()->Lojas_idLojas)
            ->where('c.bairro', 'LIKE', $bairro)
            ->get([
                'c.id',
                'c.taxa_entrega',
                'c.vlr_minimo_pedido',
                'c.logradouro'
            ]);
    }

    public function update($id, Request $request)
    {
        // Change Performance
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 1800);
        ini_set('max_input_vars', 500000);

        $ids = [];
        foreach ($request->enderecos as $endereco) {
            array_push($ids, $endereco['id']);
            Ceps::where('id', $endereco['id'])
                ->update([
                    'id' => $endereco['id'],
                    'taxa_entrega' => $endereco['taxa_entrega'] ? (double)$endereco['taxa_entrega'] : 0,
                    'vlr_minimo_pedido' => $endereco['vlr_minimo_pedido'] ? (double)$endereco['vlr_minimo_pedido'] : 0
                ]);
        }
        $lojaObj = new Loja();
        $loja = $lojaObj->find(auth('api_painel')->user()->Lojas_idLojas);
        $loja->ceps()->attach($ids);

        return response()->json(['message' => 'CEP\'s vinculados com Sucesso!'], 201);
    }

//    public function editaCeps($id, Request $request, Ceps $ceps)
//    {
//        $this->authorize('loja_edita_cep');
//
//        $cepLojaObj = new Ceps_loja();
//        $dataForm = $request->except('_token')['Enderecos'];
//
//        foreach ($dataForm as $index => $endereco) {
//            if (isset($endereco['Ceps_idCeps'])) {
//                $ceps->find($endereco['Ceps_idCeps'])
//                    ->update([
//                        'taxa_entrega' => $endereco['taxa_entrega'],
//                        'vlr_minimo_pedido' => $endereco['vlr_minimo_pedido']
//                    ]);
//            }
//        }
//        $bairros = $ceps->select('bairro')->groupBy('bairro')->get();
//        $Loja = $this->Loja->find($id);
//        flash('CEPs da Loja: ' . $Loja->razao_social . '. Editados com sucesso !', 'success');
//        return view('Painel.Administrativo.Loja.gerenciar.gerenciar', compact('bairros', 'Loja'));
//    }
//
//    public function desvinculaCeps($id, Request $request, Ceps $ceps)
//    {
//        $this->authorize('loja_desvincula_cep');
//
//        $cepLojaObj = new Ceps_loja();
//        $dataForm = $request->except('_token')['Enderecos'];
//
//        foreach ($dataForm as $index => $endereco) {
//            if (isset($endereco['Ceps_idCeps'])) {
//                $cepLojaAux = $cepLojaObj->where('Lojas_idLojas', $id)
//                    ->where('Ceps_idCeps', $endereco['Ceps_idCeps'])
//                    ->first();
//                $cepLojaAux->delete();
//            }
//        }
//        $bairros = $ceps->select('bairro')->groupBy('bairro')->get();
//        $Loja = $this->Loja->find($id);
//        flash('CEPs Desvinculados com sucesso da Loja: ' . $Loja->razao_social . '!', 'success');
//        return view('Painel.Administrativo.Loja.gerenciar.gerenciar', compact('bairros', 'Loja'));
//    }
}
