<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\CupomFormRequest;
use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends ApiController
{
    public function __construct(Cupom $cupom)
    {
        parent::__construct($cupom);
    }

    public function tipos()
    {
        //TODO: Depois Acrescentar $this->Model->getCodIndividuais()
        return response()->json($this->Model->getCodPromocionais());
    }

    public function store(CupomFormRequest $request)
    {
        $data = [
            'codigo'        => $request->codigo,
            'quantidade'    => $request->quantidade,
            'validade'      => $request->validade
        ];
        $codigo = $request->codigo;
        switch ($codigo){
            case "A":
            case "N":
                if ($codigo==='A'){ $data['hash'] = $request->hash; }
                break;
            case "B":
            case "O":
                if ($codigo==='B'){ $data['hash'] = $request->hash; }
                $data['porcentagem'] = $request->porcentagem;
                break;
            case "C":
            case "P":
                if ($codigo==='C'){ $data['hash'] = $request->hash; }
                $data['valor'] = $request->valor;
                break;
            case "D":
            case "Q":
                if ($codigo==='D'){ $data['hash'] = $request->hash; }
                $data['Cat_produtos_idCat_produtos'] = $request->Cat_produtos_idCat_produtos;
                break;
            case "E":
            case "R":
                if ($codigo==='E'){ $data['hash'] = $request->hash; }
                $data['Produtos_idProdutos'] = $request->Produtos_idProdutos;
                break;
        }

        $cupom = $this->Model->create($data);
        return response()->json($cupom);
    }

    public function update(CupomFormRequest $request, $id)
    {
        $cupom = $this->Model->find($id);
        $cupom->update($request->all());
        return response()->json($cupom);
    }
}
