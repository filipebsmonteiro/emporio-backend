<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Http\Resources\API\ProdutoCollection;
use App\Http\Resources\API\Produto as ProdutoResource;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends ApiController
{
    public function __construct(Produto $produto)
    {
        parent::__construct($produto);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
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
