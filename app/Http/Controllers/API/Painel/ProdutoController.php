<?php


namespace App\Http\Controllers\API\Painel;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\ProdutoFormRequest;
use App\Http\Resources\API\ProdutoPainel as ProdutoPainelResource;
use App\Http\Resources\API\ProdutoPainelCollection;
use App\Http\Traits\AttachIngredientesMultiplos;
use App\Http\Traits\AttachProdutosMultiplos;
use App\Models\Ingrediente_multiplo;
use App\Models\Produto;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProdutoController extends ApiController
{
    use AttachIngredientesMultiplos;
    use AttachProdutosMultiplos;

    public function __construct(Produto $produto)
    {
        parent::__construct($produto);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->filterByLoja('api_painel');
        $this->executeQuery($request);
        return new ProdutoPainelCollection($this->Results);
    }

    public function show($id)
    {
        $produto = $this->Model->find($id);
        return response()->json(new ProdutoPainelResource($produto));
    }

    public function attachImagem(Produto $produto, Request $request): ?string
    {
        $uploaded = $request->file('imagem');
        if ($uploaded) {
            $extension = $uploaded->getMimeType();
            if ($extension === 'image/jpeg' || $extension === 'image/png') {
                $extension = $uploaded->getClientOriginalExtension();
                $auxImagem = $produto->categoria->id . '-' . $produto->id . '.' . $extension;

                $uploaded->move(public_path() . '/imagens/produtos', $auxImagem);
                $produto->update(['imagem' => $auxImagem]);
            } else {
                throw new BadRequestHttpException('Arquivo deve ser JPG ou PNG !', null, 400);
            }
            return $auxImagem;
        }
        return null;
    }

    protected function attachIngredientes(Produto $produto, array $ingredientes): bool
    {
        $shouldAttach = [];
        foreach ($ingredientes as $ingrediente) {
            $shouldAttach[$ingrediente['id']] = ['visibilidade' => $ingrediente['visibilidade']];
        }
        $produto->ingredientes()->sync($shouldAttach);
        return true;
    }

    public function uploadImagem(Request $request)
    {
        $produto = $this->Model->find($request->id);
        $nomeimagem = $this->attachImagem($produto, $request);
        return response()->json(['imagem' => $nomeimagem]);
    }

    public function store(ProdutoFormRequest $request)
    {
        DB::beginTransaction();
        try {
            $dataForm = $request->all();
            $verify = [
                'nome' => $dataForm['nome'],
                'Lojas_idLojas' => auth('api_painel')->user()->loja->id,
                'Cat_produtos_idCat_produtos' => $dataForm['Cat_produtos_idCat_produtos']
            ];

            $produto = $this->Model->firstOrCreate(
                $verify,
                array_merge($verify, $dataForm)
            );
            if ($produto->exists) {
                $produto->update($dataForm);
            }

            $this->attachProdutosMultiplos($produto, $request->combinacoes);

            $this->attachMultiplos($produto, $request->multiplos);

            $this->attachIngredientes($produto, $request->ingredientes);

        } catch (QueryException $exception) {
            DB::rollback();
            $Message = $exception->getMessage();
            throw new BadRequestHttpException($Message, null, 400);
        } catch (BadRequestHttpException $exception) {
            DB::rollback();
            throw new BadRequestHttpException($exception->getMessage(), null, 400);
        }
        DB::commit();
        return new ProdutoPainelResource($produto);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $produto = $this->Model->find($id);
            $produto->update($request->all());

            $this->attachProdutosMultiplos($produto, $request->combinacoes);

            $this->attachMultiplos($produto, $request->multiplos);

            $this->attachIngredientes($produto, $request->ingredientes);

        } catch (QueryException $exception) {
            DB::rollback();
            $Message =
                'Desculpe, Estamos tendo problemas a editar o produto. - ' .
                $exception->getMessage();
            throw new BadRequestHttpException($Message, null, 400);
        } catch (BadRequestHttpException $exception) {
            DB::rollback();
            throw new BadRequestHttpException($exception->getMessage(), null, 400);
        }
        DB::commit();
        return new ProdutoPainelResource($produto);
    }

    public function changeStatus(Request $request, $id)
    {
        $produto = $this->Model->find($id);
        $produto->update(['status' => $request->status]);
        return new ProdutoPainelResource($produto);
    }
}
