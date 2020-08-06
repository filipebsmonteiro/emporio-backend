<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\Cat_produtoFormRequest;
use App\Http\Resources\API\Cat_produto;
use App\Http\Traits\AttachIngredientesMultiplos;
use App\Models\Categoria_produto;
use App\Models\Ingrediente_multiplo;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CategoriaProdutoController extends ApiController
{
    use AttachIngredientesMultiplos;

    public function __construct(Categoria_produto $categoria)
    {
        parent::__construct($categoria);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->filterByLoja('api_painel');
        $this->executeQuery($request);
        return response()->json($this->Results);
    }

    public function show($id)
    {
        $categoria = $this->Model->find($id);
        return response()->json(new Cat_produto( $categoria ));
    }

    public function store(Cat_produtoFormRequest $request)
    {
        DB::beginTransaction();
        try {
            $dataForm = $request->all();
            $verify = [
                'nome' => $dataForm['nome'],
                'Lojas_idLojas' => $this->user->loja->id
            ];

            $categoria = $this->Model->firstOrCreate(
                $verify,
                array_merge($verify, $dataForm)
            );
            if ($categoria->exists) {
                $categoria->update($dataForm);
            }

            $this->attachMultiplos($categoria, $request->multiplos);

        }catch (QueryException $exception){
            DB::rollback();
            $Message = $exception->getMessage();
            throw new BadRequestHttpException($Message, null, 400);
        }catch (BadRequestHttpException $exception){
            DB::rollback();
            throw new BadRequestHttpException($exception->getMessage(), null, 400);
        }
        DB::commit();
        return new Cat_produto($categoria);
    }

    public function update(Cat_produtoFormRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $produto = $this->Model->find($id);
            $produto->update( $request->all() );

            $this->attachMultiplos($produto, $request->multiplos);

        }catch (QueryException $exception){
            DB::rollback();
            $Message =
                'Desculpe, Estamos tendo problemas a editar a categoria. - '.
                $exception->getMessage();
            throw new BadRequestHttpException($Message, null, 400);
        }catch (BadRequestHttpException $exception){
            DB::rollback();
            throw new BadRequestHttpException($exception->getMessage(), null, 400);
        }
        DB::commit();
        return new Cat_produto($produto);
    }
}
