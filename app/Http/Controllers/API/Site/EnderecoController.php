<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\SiteEnderecoFormRequest;
use App\Models\Ceps;
use App\Models\Ceps_loja;
use App\Models\EnderecoCliente;
use App\Models\Loja;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EnderecoController extends ApiController
{
    public function __construct(EnderecoCliente $endereco)
    {
        parent::__construct($endereco);
    }

    public function list(Request $request)
    {
        $filters = $request->all();
        $filters[] = ['Clientes_idClientes', '=', auth('api')->user()->getAuthIdentifier()];
        $results = $this->Model->where($filters)->get();
        return response()->json($results);
    }

    public function index(Request $request)
    {
        $filters = [['Clientes_idClientes', '=', auth('api')->user()->getAuthIdentifier()]];
        $enderecos = $this->Model->where($filters)->get();
        return response()->json($enderecos);
    }

    public function store(SiteEnderecoFormRequest $request)
    {
        $dataForm = $request->all();
        $dataForm['Clientes_idClientes'] = auth('api')->user()->getAuthIdentifier();

        //Valida Se a Loja Atende Cep Informado
        $this->getLojaResponsavel($dataForm['CEP']);

        $endereco = $this->Model
            ->where([
                'CEP' => $dataForm['CEP'],
                'Clientes_idClientes' => $dataForm['Clientes_idClientes']
            ])->onlyTrashed()
            ->first();
        if ($endereco && $endereco->trashed()) {
            $endereco->restore();
            return response()->json($endereco);
        }

        $endereco = $this->Model->create($dataForm);
        return response()->json($endereco);

    }

    /**
     * Recebe o CEP e retorna Loja Responsável
     * Ou Mensagem de Erro
     *
     * @param $CEP
     * @return mixed
     */
    public function getLojaResponsavel($CEP)
    {
        $CepObj = new Ceps();
        $cepstring = str_replace(str_split('.-'), "", $CEP);
        $cep = $CepObj->where('CEP', $cepstring)->first();
        if (!$cep) {
            throw new BadRequestHttpException('Esta loja não efetua entregas neste CEP!');
        }

        $cepLojaObj = new Ceps_loja();
        $CepLoja = $cepLojaObj->where('Ceps_idCeps', $cep->id)->first();
        if (!$CepLoja) {
            throw new BadRequestHttpException('Esta loja não atende temporariamente nesse CEP!');
        }

        $lojaObj = new Loja();
        return $lojaObj->find($CepLoja->Lojas_idLojas);
    }

    public function update(SiteEnderecoFormRequest $request, $id)
    {
        $endereco = $this->Model->find($id);
        if (auth('api')->user()->getAuthIdentifier() != $endereco->Clientes_idClientes) {
            throw new \HttpResponseException('Operação não autorizada!');
        }

        $endereco->update($request->all());
        return response()->json($endereco);
    }
}
