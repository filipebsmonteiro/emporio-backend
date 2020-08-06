<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 18/02/2019
 * Time: 09:41
 */

namespace App\Http\Controllers\Painel\Loja;

use App\Http\Resources\PedidoCollection;
use App\Models\Cliente;
use App\Models\EnderecoCliente;
use App\Models\FormaPagamento;
use App\Models\Ing_prod_pedido;
use App\Models\Ingrediente;
use App\Models\Loja;
use App\Models\Pedido;
use App\Models\PedidoFormaPagamento;
use App\Models\Pedidos_produto;
use App\Models\Produto;
use iFood\API\Ecommerce\IfoodEcommerce;
use iFood\API\Merchant;
use Illuminate\Support\Facades\Auth;

class IfoodController
{
    private $Environment;
    private $Merchant;
    private $iFoodEcommerce;

    public function __construct()
    {
//        Pega usuario logado
        $logado             = Auth::user();

//        Configure o ambiente
        $this->Environment  = Environment::production();

//        Pega a loja do usuario logado
//        $lojaObj            = new Loja();
//        $loja               = $lojaObj->find( $logado->Lojas_idLojas );

//        Configure o merchant
//        $this->Merchant     = new Merchant(
//            $loja->iFood_Merchant, $loja->iFood_Secret,
//            $logado->iFood_Username, $logado->iFood_Password
//        );
        $this->Merchant     = new Merchant($logado->iFood_Username, $logado->iFood_Password);

        $this->iFoodEcommerce   = new IfoodEcommerce($this->Merchant);
    }

    public function tokenizaLojista()
    {
        return $this->iFoodEcommerce->tokenizeMerchant();
    }

    public function carregaVendas(Cliente $clienteObj, EnderecoCliente $endClienteObj,
                                  FormaPagamento $formaPgtoObj, Pedido $pedidoObj)
    {
//        Carrega Pedidos do iFood não sincronizados no site
        $events         = $this->iFoodEcommerce->listenEvents();
        $eventoIds      = [];
        $pedidoIds      = [];

        foreach($events as $event){

//            Zera Status para cada pedido
            $status         = '';

//            Converte o Status do pedido no iFood para o Site
            switch ($event->code){
                case 'PLACED':
//                    Indica um pedido foi colocado no sistema.
                    $status     = 'Pedido Realizado';
                    break;
                case 'CONFIRMED':
//                    Indica um pedido confirmado.
                    $status     = 'Em Fabricação';
                    break;
                case 'INTEGRATED':
//                    Indica um pedido que foi recebido pelo e-PDV.
                    $status     = 'Pedido Realizado';
                    break;
                case 'CANCELLED':
//                    Indica um pedido que foi cancelado.
                    $status     = 'Cancelado';
                    break;
                case 'DISPATCHED':
//                    Indica um pedido que foi despachado ao cliente.
                    $status     = 'Enviado';
                    break;
                case 'DELIVERED':
//                    Indica um pedido que foi entregue.
                    $status     = 'Concluido';
                    break;
                case 'CONCLUDED':
//                    Indica um pedido que foi concluído (Em até duas horas do fluxo normal)*.
                    $status     = 'Concluido';
                    break;
            }

//            Grava pedido do iFood no site
            $pedido     = $pedidoObj->updateOrCreate(
                [
                    'referencia'            => 'iFood_'.$event->correlationId,
                    'Lojas_idLojas'         => Auth::user()->Lojas_idLojas
                ],[
                    'status'                => $status,
                    'valor'                 => 0.0,
                    'taxaEntrega'           => 0.0,
                    'Clientes_idClientes'   => 1,
                    'Enderecos_idEnderecos' => 1
                ]
            );

//            Lê informações do Pedido baseado no correlationId do iFood
            $sale       = $this->iFoodEcommerce->readSale($event->correlationId);

            /*
             * BLOCO
             * Atualiza informações do Pedido vindo do iFood
             */

//            Cria ou atualiza cliente do iFood
            $cliente    = $clienteObj->updateOrCreate(
                [
                    'CPF'       => $sale->getCustomer()->getTaxPayerIdentificationNumber()
                ],[
                    'nome'      => $sale->getCustomer()->nome,
                    'sexo'      => 'Não Informar',
                    'phone'     => $sale->getCustomer()->phone,
                    'email'     => $sale->getCustomer()->taxPayerIdentificationNumber.'@ifood.com',
                    'password'  => bcrypt('123456')
                ]
            );

//            Taxa de Entrega inicialmente está como Retirada na Loja
            $taxaEntrega        = 0;
            if( $sale->getType() != 'TOGO' ){
//                Type será igual a DELIVERY
                $taxaEntrega    = $sale->getDeliveryFee();
                $endClienteObj->updateOrCreate(
                    [
                        'CEP'   => $sale->getDeliveryAddress()->getPostalCode(),
                        'Clientes_idClientes' => $cliente->id
                    ],[
                        //           streetName, streetNumber, complement
                        'Logradouro'          => $sale->getDeliveryAddress()->getFormattedAddress(),
                        'Bairro'              => $sale->getDeliveryAddress()->getNeighborhood(),
                        'Cidade'              => $sale->getDeliveryAddress()->getCity(),
                        'Referencia'          => $sale->getDeliveryAddress()->getReference(),
                        'UFs_idUFs'           => 1
                    ]
                );
            }

//            Integra pedido do iFood no site
            $pedido->update([
                'valor'                 => $sale->getTotalPrice(),
                'taxaEntrega'           => $taxaEntrega,
                'Clientes_idClientes'   => $cliente->id
            ]);

//            Carrega Objetos a serem utilizados para Produtos desse Pedido
            $pedProdObj     = new Pedidos_produto();
            $prodObj        = new Produto();

//            A cada Pedido Zera as observações
            $auxObs         = '';

            foreach( $sale->getItems() as $item ){
                $produto    = $prodObj->where('codigo_PDV', $item->externalCode)->first();
                $pedProd    = $pedProdObj->create([
                    'quantidade'            => $item->quantity,
                    'valor'                 => $item->price,
                    'Produtos_idProdutos'   => $produto->id,
                    'Pedidos_idPedidos'     => $pedido->id
                ]);

//                Verifica esse Produto nesse Pedido possui subIngredientes
                if ( $item->subItems ) {
//                    Carrega Objetos a serem utilizados para subIngredientes desse Produto desse Pedido
                    $ingObj         = new Ingrediente();
                    $ingProdPedObj  = new Ing_prod_pedido();

//                    Vincula subIngredientes ao Produto desse Pedido
                    foreach ($item->subItems as $subItem) {
                        $ingrediente = $ingObj->where('codigo_PDV', $subItem->externalCode)->first();
                        $ingProdPedObj->create([
                            'quantidade'                    => $subItem->quantity,
                            'valor'                         => $subItem->price,
//                            'Ped_prod_mult_idPed_prod_mult' ,
                            'Ped_produtos_idPed_produtos'   => $pedProd->id,
                            'Ingredientes_idIngredientes'   => $ingrediente->id
                        ]);
                      }
                }

//                Adiciona observações do Produto ao Pedido
                if( $item->observations ){
                    $auxObs = $auxObs . ' ' . $item->name . ' - ' . $item->observations.';';
                }
            }

            $pedFormaObj    = new PedidoFormaPagamento();

//            Vincula as Formas de Pagamento ao Pedido
            foreach( $sale->getPayments() as $formaIfood ) {
                $forma      = $formaPgtoObj->where('codigo_PDV', $formaIfood->externalCode)->first();
                $pedFormaObj->updateOrCreate(
                    [
                        'Pedidos_idPedidos' => $pedido->id,
                        'Formas_idFormas' => $forma->id
                    ],[
                        'troco' => $formaIfood->value
                    ]
                );
            }

            /*
             *
             * TRIGGER
             * NEW
             * PEDIDO
             * EVENT
             *
             */

            /*
             * FIM BLOCO
             */

            array_push($eventoIds, ['id' => $event->id]);
            array_push($pedidoIds, $pedido->id);
        }

//        Limpa fila de pedidos integrados no iFood
//        $this->iFoodEcommerce->cleanSales(json_encode($arrayIds));

//        Retorna Todos Pedidos do iFood
        return new PedidoCollection( $pedidoObj->whereIn($pedidoIds)->get() );
    }

    public function atualizaVenda(Pedido $pedido)
    {
//            Converte o Status do pedido no Site para o iFood
        switch ( $pedido->status ){
            case 'Pedido Realizado':
//                Informa ao IFood que o pedido foi integrado pelo e-PDV.
                $status     = 'integration';
                break;
            case 'Em Fabricação':
//                Informa ao IFood que o pedido foi confirmado pelo e-PDV.
                $status     = 'confirmation';
                break;
            case 'Enviado':
//                Informa ao IFood que o pedido saiu para ser entregue ao cliente.
                $status     = 'dispatch';
                break;
            case 'Cancelado':
//                Informa ao IFood que o pedido foi rejeitado pelo e-PDV.
                $status     = 'rejection';
                break;
            case 'Concluido':
//                Informa ao IFood que o pedido foi entregue ao cliente.
                $status     = 'delivery';
                break;
        }

        $pieces             = explode("iFood_", $pedido->referencia);
        $correlationId      =  $pieces[1];

        return $this->iFoodEcommerce->updateSale($correlationId, $status);
    }
}