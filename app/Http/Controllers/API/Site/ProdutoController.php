<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Http\Resources\API\ProdutoCollection;
use App\Http\Resources\API\Produto as ProdutoResource;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProdutoController extends ApiController
{

    public function __construct(Produto $produto)
    {
        parent::__construct($produto);
    }

    protected function checkPromocao(): void
    {
        $wheres = $this->Query->getQuery()->wheres;
        foreach ($wheres as $where) {
            if(
                is_array($where) && key_exists('query', $where) &&
                $where['query']->wheres[0]['column'] === 'Cat_produtos_idCat_produtos' &&
                $where['query']->wheres[0]['type'] === 'Null'
            ){
                $this->Query = $this->Model
                    ->newQuery()
                    ->where('promocionar', true);
                $this->addDisponibilidadeFilter();
            }
        }
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->checkPromocao();
        $this->executeQuery($request);
        return response()->json(new ProdutoCollection( $this->Results ));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $entity = $this->Model->find($id);
        return response()->json(new ProdutoResource( $entity ));
    }
}
