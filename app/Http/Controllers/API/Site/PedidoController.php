<?php


namespace App\Http\Controllers\API\Site;


use App\Events\pedidoEvent;
use App\Http\Controllers\API\ApiController;
use App\Http\Requests\API\SitePedidoRequest;
use App\Http\Resources\API\Pedido as PedidoResource;
use App\Http\Resources\API\PedidoCollection;
use App\Models\Ceps;
use App\Models\Cupom;
use App\Models\EnderecoCliente;
use App\Models\Fidelidade;
use App\Models\Ingrediente_multiplo;
use App\Models\Pedido;
use App\Models\Pedidos_produto;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PedidoController extends ApiController
{
    public function __construct(Pedido $pedido)
    {
        parent::__construct($pedido);
    }

    public function index(Request $request)
    {
        $this->prepareQuery($request);
        $this->Query = $this->Query->where(
            'Clientes_idClientes', '=', auth('api')->user()->getAuthIdentifier()
        )->orderBy('created_at', 'desc');
        $this->executeQuery($request);
        return response()->json(new PedidoCollection( $this->Results ));
    }

    public function show($id)
    {
        $entity = $this->Model->where('referencia', $id)->first();
        return response()->json(new PedidoResource($entity));
    }

    public function store(SitePedidoRequest $request)
    {
        DB::beginTransaction();
        try {
            $pedido = $this->generate($request);
            if ($pedido->exists) {
                return new PedidoResource($pedido);
            }
            $pedido->save();

            $valorProdutos = $this->attachProdutos($pedido, $request->produtos);
            $pedido->update([
                'valor' => $valorProdutos,
                'taxaEntrega' => $this->calculaValorFrete($pedido)
            ]);

            $this->validaValoresMinimos($pedido);

            if ($request->cupom_field) {
                //TODO: ALTERAR VALOR DO PEDIDO
                $this->applyCupom($pedido, $request->cupom_field);
            }

            if ($request->fidelidade_field) {
                //TODO: ALTERAR VALOR DO PEDIDO
                $this->applyFidelidade($pedido, $request->fidelidade_field);
            }

            $pedido->formaPagamento()->attach($request->forma_pagamento, [
                'troco' => $request->troco
            ]);

            // Notifica Atendentes
            //$evtPedido  = new pedidoEvent( $pedido,'novo-pedido'.$pedido->Lojas_idLojas );
            $evtPedido = new pedidoEvent($pedido, 'novo-pedido');
            event($evtPedido);

            //TODO: checkoutReact gera pagamento com relacionametos antigos
            /*/ Caso seja pagamento online gera pagamento
            if ($pedido->formaPagamento->first()->id === 2) {

                $cieloCtrlr = new CieloController($pedido->loja->CieloMerchantId, $pedido->loja->CieloMerchantKey);
                $cieloCtrlr->geraVenda($pedido);
                $response   = $cieloCtrlr->checkoutReact();

                return response()
                    ->json([
                        'message' => 'Pedido Okay, aguardando pagamento!',
                        'URIreturn' => $response->settings->checkoutUrl
                    ]);
            }*/
        } catch (QueryException $exception) {
            DB::rollback();
            $Message =
                'Desculpe, Estamos tendo problemas a registrar seu pedido. - ' .
                $exception->getMessage();
            throw new BadRequestHttpException($Message, null, 400);
        } catch (BadRequestHttpException $exception) {
            DB::rollback();
            throw new BadRequestHttpException($exception->getMessage(), null, 400);
        }
        DB::commit();
        return new PedidoResource($pedido);

        /*
//        Verifica senão é Cliente: Usuario Administrativo
        if( $pedido->Clientes_idClientes > 1 ) {



        }else{
//            Se for Teste:

            if ( isset($Teste['cleanCart']) ){
//                Esvazia o Carrinho
                $cookieP    = cookie('pedido', '', 0.1);
            }else{
                $cookie     = $request->cookies->get('pedido');
                $cookieP    = cookie('pedido', $cookie, 60);
            }

            if ( isset($Teste['cleanAddress']) ){
//                Esvazia o Endereço
                $cookieE    = cookie('EnderecoPedido', '', 0.1);
            }else{
                $cookie     = $request->cookies->get('EnderecoPedido');
                $cookieE    = cookie('EnderecoPedido', $cookie, 60);
            }

            if ( isset($Teste['Notify']) ){
//            Notifica Atendentes
                $ePedido    = new pedidoEvent( $pedido,'novo-pedido-'.$pedido->Lojas_idLojas );
                event($ePedido);
            }

            if ( isset($Teste['OnlinePayment']) ){
                if ($isPgtoOnline) {

                    $cieloCtrlr = new CieloController($loja['CieloMerchantId'], $loja['CieloMerchantKey']);
                    $cieloCtrlr->geraVenda($pedido);
                    $response   = $cieloCtrlr->checkoutReact();

                    return response()
                        ->json(['message' => 'Pedido Okay, aguardando pagamento!', 'URIreturn' => $response->settings->checkoutUrl])
                        ->withCookie($cookieP)
                        ->withCookie($cookieE);
//                    return redirect($response->settings->checkoutUrl)
//                                ->withCookie($cookieP)
//                                ->withCookie($cookieE);

                }else{
                    throw new BadRequestHttpException("Não foi possível gerar pagamento!", null, 400);
                }
            }

        }

        return response()
            ->json(['message' => 'Pedido Finalizado com Sucesso!', 'URIreturn' => route('pedido.show', $pedido->referencia)])
            ->withCookie($cookieP)
            ->withCookie($cookieE);
        */
    }

    protected function generate(SitePedidoRequest $request): Pedido
    {
        $cliente = auth('api')->user();
        $pedido = $this->Model
            ->where('Enderecos_idEnderecos', $request->endereco_id)
            ->where('Clientes_idClientes', $cliente->id)
            ->where('Lojas_idLojas', $request->loja_id)
            ->where('created_at', '>=', Carbon::now()->subMinutes(2)->toDateTimeString())
            ->first();

        if (!$pedido) {
            $pedido = new Pedido([
                'agendamento' => $request->agendamento,
                'Clientes_idClientes' => $cliente->id,
                'Enderecos_idEnderecos' => $request->endereco_id,
                'ip_address' => $request->getClientIp(),
                'Lojas_idLojas' => $request->loja_id,
                'observacoes' => $request->observacoes,
                'referencia' => $this->uniqidReal(),
                'status' => 'Pedido Realizado',
                'taxaEntrega' => 0,
                'valor' => 0,
            ]);
        }

        return $pedido;
    }

    public function uniqidReal($lenght = 10)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    protected function attachProdutos(Pedido $pedido, array $produtos): float
    {
        $valorProdutos = 0;

        foreach ($produtos as $produto) {
            $pedido_produto = new Pedidos_produto([
                'Pedidos_idPedidos' => $pedido->id,
                'Produtos_idProdutos' => $produto['produto'],
                'quantidade' => $produto['quantidade'],
                'valor' => 0
            ]);
            $pedido_produto->save();

            $valorCombinacoes = $this->attachCombinacoes($pedido_produto, $produto['combinacoes']);

            $valorMultiplos = $this->attachMultiplos($pedido_produto, $produto['multiplos']);

            // Seta Valor daquele Produto naquele Pedido
            $prodObj = new Produto();
            $prod = $prodObj->find($produto['produto']);
            $valorProduto = ($prod->promocionar ? $prod->valorPromocao : $prod->preco);
            $minimo_unidade = $prod->minimo_unidade ? $prod->minimo_unidade : 1;

            $custoProduto = (($valorProduto / $minimo_unidade) + $valorCombinacoes) / (sizeof($produto['combinacoes']) + 1);
            $custoProduto = ($custoProduto + $valorMultiplos) * $produto['quantidade'];
            $valorProdutos += $custoProduto;
            $pedido_produto->update(['valor' => $custoProduto]);
        }

        return $valorProdutos;
    }

    protected function attachMultiplos(Model $entity, array $multiplos): float
    {
        $valorMultiplos = 0;

        foreach ($multiplos as $multiplo) {
            $valorMultiplo = $this->calculaValorMultiplo(
                $multiplo['multiplo'],
                $multiplo['ingrediente'],
                $multiplo['quantidade']
            );
            //$entity->multiplos()->attach($multiplo['multiplo'], [
            $entity->multiplo()->attach($multiplo['multiplo'], [
                'Ingredientes_idIngredientes' => $multiplo['ingrediente'],
                'quantidade' => $multiplo['quantidade'],
                'valor' => $valorMultiplo
            ]);
            $valorMultiplos += $valorMultiplo;
        }

        return $valorMultiplos;
    }

    protected function attachCombinacoes(Model $entity, array $combinacoes): float
    {
        $valorCombinacoes = 0;

        foreach ($combinacoes as $combinacao) {
            $relation = [
                'quantidade' => 1,
                'valor' => $combinacao['preco'] ?? 0,
                'Ped_prod_mult_idPed_prod_mult' => $combinacao['multiplo_id'] ?? null
            ];

            // SubProdutos Layout Combo
            if (isset($combinacao['multiplo_id'])){
                foreach ($combinacao['combinacoes'] as $subcombinacao){
                    $entity->combinacoes()->attach($subcombinacao['id'], [
                        'quantidade' => 1,
                        'Ped_prod_mult_idPed_prod_mult' => $combinacao['multiplo_id'],
                        'valor' => $subcombinacao['preco'] ?? 0
                    ]);
                }
            }
            $entity->combinacoes()->attach($combinacao['id'], $relation);
            $valorCombinacoes += $relation['valor'];
        }

        return $valorCombinacoes;
    }

    protected function calculaValorMultiplo(int $multiplo_id, int $ingrediente_id, int $quantidade): float
    {
        $ingMultiplo = new Ingrediente_multiplo();
        $multiplo = $ingMultiplo->find($multiplo_id);
        $ingrediente = $multiplo->ingredientes->where('id', $ingrediente_id)->first();

        if ($ingrediente->pivot->valor > 0) {
            return $ingrediente->pivot->valor;
        }

        return $quantidade * $ingrediente->preco;
    }

    protected function calculaValorFrete(Pedido $pedido): float
    {
        if ($pedido->Enderecos_idEnderecos > 1) {
            $endObj = new EnderecoCliente();
            $endCtrl = new EnderecoController($endObj);
            $endCtrl->getLojaResponsavel($pedido->enderecoEntrega->CEP);
            /** @var Ceps $cep */
            $cep = $pedido->enderecoEntrega->cep();
            if (!$cep) {
                throw new UnprocessableEntityHttpException('Cep não registrado');
            }
            return $cep->taxaEntrega;
        }
        return 0;
    }

    protected function validaValoresMinimos(Pedido $pedido): bool
    {
        if (($pedido->valor + $this->calculaValorFrete($pedido)) < $pedido->loja->vlr_minimo_pedido) {
            $message = 'Valor minimo de pedido para esta loja é: R$ ' . $pedido->loja->vlr_minimo_pedido;
            throw new BadRequestHttpException($message, null, 400);
        }
        if ($pedido->Enderecos_idEnderecos > 1) {
            if (($pedido->valor + $this->calculaValorFrete($pedido)) < $pedido->enderecoEntrega->cep()->vlr_minimo_pedido) {
                $message = 'Valor minimo de pedido para o endereço selecionado é: R$ ' . $pedido->enderecoEntrega->cep->vlr_minimo_pedido;
                throw new BadRequestHttpException($message, null, 400);
            }
        }
        return true;
    }

    protected function applyCupom(Pedido $pedido, string $field): void
    {
        /*
         * ATENÇÃO CUPONS NÂO ALTERAM VALOR DO SUBTOTAL
         *
         * CUPONS:
         * Caso o cupom seja válido pode impctar:
         * - Valor do pedido (subTotal)
         * - Abater a taxa de entrega
         */
        $cupomObj = new Cupom();
        $cupomCtrl = new CupomController($cupomObj);
        $codigo = explode('-', $field);
        $cupom = $cupomCtrl->validaRegrasCupom($codigo[0], $codigo[1]);
        $pedido->cupons()->attach($cupom->id);
    }

    protected function applyFidelidade(Pedido $pedido, string $field): void
    {
        /*
         * ATENÇÃO FIDELIDADE NÂO ALTERA VALOR DO SUBTOTAL
         *
         * FIDELIDADE:
         * - Resgates também são gravados a parte
         */
        $fidelidadeObj = new Fidelidade();
        $fidelidadeCtrl = new FidelidadeController($fidelidadeObj);
        $valorDisponivel = $fidelidadeCtrl->valorResgate($pedido->cliente->id);
        $valorResgate = $field;
        if ($valorResgate > $valorDisponivel) {
            throw  new BadRequestHttpException('Valor inforado de Fidelidade incompatível com valor disponível para resgate!');
        }
        if ($valorResgate > ($pedido->valor + $pedido->taxaEntrega)) {
            $valorResgate = ($pedido->valor + $pedido->taxaEntrega);
        }

        // Armazena o Resgate da fidelidade
        $fidelidadeObj->create([
            'valorResgate' => $valorResgate,
            'Clientes_idClientes' => $pedido->cliente->id,
            'Lojas_idLojas' => $pedido->loja->id,
            'Pedidos_idPedidos' => $pedido->id
        ]);
    }
}
