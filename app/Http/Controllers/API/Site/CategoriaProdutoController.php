<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Http\Resources\API\CombinacaoCollection;
use App\Models\Categoria_produto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoriaProdutoController extends ApiController
{
    private $diaSemana;

    public function __construct(Categoria_produto $categoria)
    {
        parent::__construct($categoria);
        switch ( Carbon::today()->dayOfWeek ) {
            case 0:
                $this->diaSemana = 'domingo';
                break;
            case 1:
                $this->diaSemana = 'segunda';
                break;
            case 2:
                $this->diaSemana = 'terca';
                break;
            case 3:
                $this->diaSemana = 'quarta';
                break;
            case 4:
                $this->diaSemana = 'quinta';
                break;
            case 5:
                $this->diaSemana = 'sexta';
                break;
            case 6:
                $this->diaSemana = 'sabado';
                break;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAvailable(Request $request)
    {
        $idLoja = 1;

        if ($request->loja_id){
            $idLoja = $request->loja_id;
        }

        $results = $this->Model->select('categoria_produtos.*')
            ->join('produtos','produtos.Cat_produtos_idCat_produtos','=', 'categoria_produtos.id')
            ->where('produtos.status', 'Disponível')
            ->where('categoria_produtos.Lojas_idLojas', $idLoja)
            ->where($this->diaSemana, true)
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
            })->groupBy([
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

        return response()->json($results);
    }
}
