<?php

namespace App\Http\Controllers\API\Painel;

use App\Http\Controllers\API\ApiController;
use App\Models\Ceps;
use App\Models\Ceps_loja;
use App\Models\Loja;
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
        return $this->Query->distinct('Bairro')->pluck('Bairro');
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->executeQuery($request);
        return response()->json($this->Results);
    }

    public function available($bairro)
    {
//        Lista Ceps que Ainda Não Possuem Lojas Entregando naquele Bairro
        return $this->Query
            ->from($this->table,'c')
            ->leftJoin($this->cepLojaTable . ' as cl', 'c.id', '=', 'cl.Ceps_idCeps')
            ->whereNull('cl.Ceps_idCeps')
            ->where('c.Bairro', 'LIKE', $bairro)
            ->get([
                'c.id',
                'c.Logradouro',
                'c.Cep',
                'c.taxaEntrega',
                'c.vlr_minimo_pedido'
            ])
            ->map(function ($endereco) {
                $prefixo = explode(' ', $endereco->Logradouro);
                $endereco->prefixo = $prefixo[0];
                return $endereco;
            });
    }

    public function listAttached($bairro)
    {
//        Lista Ceps que a Loja informada está Entregando naquele Bairro
        return $this->Query
            ->from($this->table, 'c')
            ->leftJoin($this->cepLojaTable . ' as cl', 'c.id', '=', 'cl.Ceps_idCeps')
            ->where('cl.Lojas_idLojas', auth('api_painel')->user()->Lojas_idLojas)
            ->where('c.Bairro', 'LIKE', $bairro)
            ->get([
                'c.id',
                'c.Logradouro',
                'c.Cep',
                'c.taxaEntrega',
                'c.vlr_minimo_pedido'
            ])
            ->map(function ($endereco) {
                $prefixo = explode(' ', $endereco->Logradouro);
                $endereco->prefixo = $prefixo[0];
                return $endereco;
            });
    }

    public function attach(Request $request)
    {
//        Change Performance
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 1800);
        ini_set('max_input_vars', 5000);

        dd($request);
        $lojaObj = new Loja();
        $loja = $lojaObj->find( auth('api_painel')->user()->Lojas_idLojas );
        foreach ($request->enderecos as $endereco) {
            $loja->ceps()->attach($endereco->id);
            $this->Query->find( $endereco->id )
                ->update([
//                'taxaEntrega'       => $endereco['taxaEntrega'],
//                'vlr_minimo_pedido' => $endereco['vlr_minimo_pedido']
                'taxaEntrega'       => $endereco->taxaEntrega ?? 0,
                'vlr_minimo_pedido' => $endereco->vlr_minimo_pedido ?? 0
            ]);
        }

        return response()->json(['message' => 'CEP\'s vinculados com Sucesso!'], 201);
    }

    public function editaCeps($id, Request $request, Ceps $ceps)
    {
        $this->authorize('loja_edita_cep');

        $cepLojaObj     = new Ceps_loja();
        $dataForm       = $request->except('_token')['Enderecos'];

        foreach ($dataForm as $index => $endereco) {
            if ( isset($endereco['Ceps_idCeps']) ){
                $ceps->find($endereco['Ceps_idCeps'])
                    ->update([
                        'taxaEntrega'       => $endereco['taxaEntrega'],
                        'vlr_minimo_pedido' => $endereco['vlr_minimo_pedido']
                    ]);
            }
        }
        $Bairros        = $ceps->select('Bairro')->groupBy('Bairro')->get();
        $Loja           = $this->Loja->find($id);
        flash('CEPs da Loja: '.$Loja->razao_social.'. Editados com sucesso !', 'success');
        return view('Painel.Administrativo.Loja.gerenciar.gerenciar', compact('Bairros','Loja'));
    }

    public function desvinculaCeps($id, Request $request, Ceps $ceps)
    {
        $this->authorize('loja_desvincula_cep');

        $cepLojaObj     = new Ceps_loja();
        $dataForm       = $request->except('_token')['Enderecos'];

        foreach ($dataForm as $index => $endereco) {
            if ( isset($endereco['Ceps_idCeps']) ){
                $cepLojaAux = $cepLojaObj->where('Lojas_idLojas', $id)
                    ->where('Ceps_idCeps', $endereco['Ceps_idCeps'])
                    ->first();
                $cepLojaAux->delete();
            }
        }
        $Bairros        = $ceps->select('Bairro')->groupBy('Bairro')->get();
        $Loja           = $this->Loja->find($id);
        flash('CEPs Desvinculados com sucesso da Loja: '.$Loja->razao_social.'!', 'success');
        return view('Painel.Administrativo.Loja.gerenciar.gerenciar', compact('Bairros','Loja'));
    }
}
