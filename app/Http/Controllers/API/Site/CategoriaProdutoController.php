<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Http\Resources\API\CombinacaoCollection;
use App\Models\Categoria_produto;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoriaProdutoController extends ApiController
{
    private $Loja_idLoja;

    public function __construct(Categoria_produto $categoria)
    {
        parent::__construct($categoria);
        $this->Loja_idLoja = 1;
    }

    public function hasPromocaoActive(): bool
    {
        $prodObj = new Produto();
        $prodQuantity = $prodObj->where('produtos.status', 'Disponível')
            ->where('Lojas_idLojas', $this->Loja_idLoja)
            ->where($this->weekDay, true)
            ->where('promocionar', true)
            ->where(function ($query) {
                $query->where('disponibilidade',	'Sempre Disponível')

                    ->orWhere('disponibilidade',	'1 Turno')
                    ->where('inicio_periodo1',		'<',	Carbon::now()->toTimeString())
                    ->where('termino_periodo1',		'>',	Carbon::now()->toTimeString())

                    ->orWhere('disponibilidade',	'2 Turnos')
                    ->where('inicio_periodo1',		'<',	Carbon::now()->toTimeString())
                    ->where('termino_periodo1',		'>',	Carbon::now()->toTimeString())
                    ->orWhere('inicio_periodo2',	'<',	Carbon::now()->toTimeString())
                    ->where('termino_periodo2',		'>',	Carbon::now()->toTimeString());
            })->count();

        return $prodQuantity > 0;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAvailable(Request $request)
    {
        if ($request->loja_id){
            $this->Loja_idLoja = $request->loja_id;
        }

        $filters = [
            ['categoria_produtos.Lojas_idLojas', '=', $this->Loja_idLoja],
            ['produtos.status', '=', 'Disponível'],
            ['JOIN', 'produtos', 'produtos.Cat_produtos_idCat_produtos', '=', 'categoria_produtos.id']
        ];

        $this->addFilters($filters);
        $this->addDisponibilidadeFilter();
        $this->Results = $this->Query
            ->groupBy([
                'id',
                'nome',
                'grupo',
                'layout',
                'created_at',
                'updated_at',
                'deleted_at',
                'permiteCombinacao',
                'quantidadeCombinacoes',
                'Lojas_idLojas'
            ])
            ->get();

        if ($this->hasPromocaoActive()){
            $this->Results
                ->prepend(
                new $this->Model([
                    'nome'          => 'Promoções',
                    'grupo'         => null,
                    'Lojas_idLojas' => $this->Loja_idLoja,
                ])
            );
        }

        return response()->json($this->Results);
    }
}
