<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\IngredienteFormRequest;
use App\Models\Ingrediente;
use App\Models\Produto;
use Illuminate\Http\Request;

class IngredienteController extends ApiController
{
    public function __construct(Ingrediente $ingrediente)
    {
        parent::__construct($ingrediente);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->filterByLoja('api_painel');
        $this->executeQuery($request);
        return response()->json($this->Results);
    }

    public function store(IngredienteFormRequest $request)
    {
        $ingrediente = $this->Model->create(array_merge(
            $request->all(),
            'Cat_ingredientes_idCat_ingredientes' => 1,
            ['Lojas_idLojas' => auth('api_painel')->user()->loja->id]
        ));

        return response()->json($ingrediente);
    }

    public function update(IngredienteFormRequest $request, $id)
    {
        $ingrediente = $this->Model->find($id);
        $ingrediente->update($request->all());
        $ingrediente = $this->Model->find($id);
        $this->updateIngProdutos($id, $ingrediente->status);

        return response()->json($ingrediente);
    }

    /**
     * Indisponibiliza Produtos de acordo com sua relação com Ingredientes
     *
     * @param $idIng
     * @param $status
     *
     */
    public function updateIngProdutos($idIng, $status): void
    {
        $Produto = new Produto();
        if ( $status==false ) {
            /*
             * Lista Produtos ATIVOS Que possuem esse Ingrediente
             * Filtrando onde é essencial
             */
            $update = $Produto->select('produtos.*')
                ->join('ingrediente_produtos',	'Produtos_idProdutos',			'=',	'produtos.id')
                ->join('ingredientes',			'Ingredientes_idIngredientes',	'=',	'ingredientes.id')

                ->where('produtos.status', 'Disponível')
                ->where('ingredientes.id', $idIng)
                ->whereIn('ingrediente_produtos.visibilidade',['Essencial Visível', 'Essencial Não Visível'])
                ->get();

            //Indisponibiliza Produtos Que possuem este ingrediente
            foreach ($update as $produto) {
                $produto->update(['status'=>'Desabilitado']);
            }
        }else {
            /*
             * Lista Produtos ATIVOS Que possuem esse Ingrediente
             * Filtrando onde é essencial
             */
            $update = $Produto->select('produtos.*')
                ->join('ingrediente_produtos',	'Produtos_idProdutos',			'=',	'produtos.id')
                ->join('ingredientes',			'Ingredientes_idIngredientes',	'=',	'ingredientes.id')

                ->where('produtos.status', 'Desabilitado')
                ->where('ingredientes.id', $idIng)
                ->whereIn('ingrediente_produtos.visibilidade',['Essencial Visível', 'Essencial Não Visível'])
                ->get();

            //Disponibiliza Produtos Que possuem este ingrediente
            foreach ($update as $produto) {
                $produto->update(['status'=>'Disponível']);
            }
        }
    }
}
