<?php

namespace App\Http\Controllers\Site;

use App\Models\Pagamento;
use App\Models\Ped_prod_prod_multiplo;
use App\Models\Pedido;
use Carbon\Carbon;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\CreditCard;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\Request\CieloError;
use Cielo\API30\Ecommerce\Request\CieloRequestException;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Merchant;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CieloController extends Controller
{
    protected $MerchantID;
    protected $MerchantKEY;

    protected $Pedido;
    protected $Environment;
    protected $Merchant;
    protected $Sale;
    protected $Customer;
    protected $Payment;
    protected $Amount;
    protected $Bandeira;
    protected $Forma;

    public function __construct($MerchantID=null, $MerchantKEY=null)
    {

//        Configure o ambiente
        $this->Environment  = Environment::production();

//        Configure seu merchant
        if( $MerchantID && $MerchantKEY ){
            $this->MerchantID   = $MerchantID;
            $this->MerchantKEY  = $MerchantKEY;
        }else{
            $this->MerchantID   = env('CIELO_MERCHANT_ID');
            $this->MerchantKEY  = env('CIELO_MERCHANT_KEY');
        }

        $this->Merchant     = new Merchant($this->MerchantID, $this->MerchantKEY);
    }

    public function geraVenda(Pedido $pedido)//, $bandeira, $forma)
    {

        $this->Pedido       = $pedido;

//        Crie uma instância de Sale informando o ID do pedido na loja
        $this->Sale         = new Sale($this->Pedido->id);

//        Crie uma instância de Customer informando o nome do cliente
        $this->Customer     = $this->Sale->customer($this->Pedido->cliente->nome);

//        Crie uma instância de Payment informando o valor do pagamento
        $this->calculaValorAPagar($pedido);
        $this->Payment      = $this->Sale->payment($this->Amount);

        return;
/*/        Associa a forma com a CONSTANTE
        switch($forma){
            case 'debito':
                $this->Forma    = Payment::PAYMENTTYPE_DEBITCARD;
                break;
            case 'credito':
                $this->Forma    = Payment::PAYMENTTYPE_CREDITCARD;
                break;
            case 'parcelado':
                $this->Forma    = Payment::PAYMENTTYPE_CREDITCARD;
                break;
            case 'boleto':
                $this->Forma    = Payment::PAYMENTTYPE_BOLETO;
                break;
            case 'transfer':
                $this->Forma    = Payment::PAYMENTTYPE_ELECTRONIC_TRANSFER;
                break;
        }

//        Associa a bandeira com a CONSTANTE
        switch ($bandeira){
            case 'visa':
                $this->Bandeira = CreditCard::VISA;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_DEBITCARD ||
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira VISA!', 'danger');
                    return;
                }
                break;
            case 'master':
                $this->Bandeira = CreditCard::MASTERCARD;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_DEBITCARD ||
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira Master Card!', 'danger');
                    return;
                }
                break;
            case 'amex':
                $this->Bandeira = CreditCard::AMEX;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira American Express!', 'danger');
                    return;
                }
                break;
            case 'elo':
                $this->Bandeira = CreditCard::ELO;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_DEBITCARD ||
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira Elo!', 'danger');
                    return;
                }
                break;
            case 'diners':
                $this->Bandeira = CreditCard::DINERS;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira Diners Club!', 'danger');
                    return;
                }
                break;
            case 'discover':
                $this->Bandeira = CreditCard::DISCOVER;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira Discover!', 'danger');
                    return;
                }
                break;
            case 'JCB':
                $this->Bandeira = CreditCard::JCB;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira JCB!', 'danger');
                    return;
                }
                break;
            case 'aura':
                $this->Bandeira = CreditCard::AURA;
                if(
                    $this->Forma != Payment::PAYMENTTYPE_CREDITCARD
                ){
                    flash('Forma de pagamento inválida para a Bandeira Aura!', 'danger');
                    return;
                }
                break;
        }
        */
    }

    public function calculaValorAPagar(Pedido $pedido)
    {
        $valorPagamento = $pedido->valor + $pedido->taxaEntrega;

//        Subtrai Fidelidade
        if ( $pedido->resgate && $pedido->resgate->first() ){
            $valorPagamento = $valorPagamento - $pedido->resgate->valorResgate;
        }

//        Subtrai descontos de Cupons
        if ( $pedido->cupons && $pedido->cupons->first() ){

            foreach($pedido->cupons as $cupom){
                if (
                    $cupom->codigo == 'D' ||
                    $cupom->codigo == 'E' ||
                    $cupom->codigo == 'J' ||
                    $cupom->codigo == 'K'
                ){
                    $valorPagamento = $valorPagamento - $cupom->pivot->valor;
                }
            }
        }

        $this->Amount   = ( intval( $valorPagamento) * 100 );//FALTA CENTAVOS + ( $valorPagamento );

        return;
    }

    /**
     * Método gera uma Venda com cartão de Crédito
     * através dos dados informados
     *
     * @param $cdSeguranca
     * @param $bandeira
     * @param $dtVencimento (mm-YYYY)
     * @param $nmrCartao
     * @param $nomeCartao
     */
    public function CreditCardPayment($codSeguranca, $dtVencimento, $nmrCartao, $nomeCartao)
    {
        // Crie uma instância de Credit Card utilizando os dados de teste
        // esses dados estão disponíveis no manual de integração

        $this->Payment->setType($this->Forma)
            ->creditCard($codSeguranca, $this->Bandeira)//Código de segurança impresso no verso do cartão
            ->setExpirationDate($dtVencimento)//Data de validade impresso no cartão
            ->setCardNumber($nmrCartao)//Número do Cartão do Comprador
            ->setHolder($nomeCartao);//Nome do Comprador impresso no cartão

        // Crie o pagamento na Cielo
        try {
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale       = (new CieloEcommerce($this->Merchant, $this->Environment))->createSale($this->Sale);
            dump($sale);

            // Com a venda criada na Cielo, já temos o ID do pagamento, TID e demais
            // dados retornados pela Cielo
            $paymentId  = $sale->getPayment()->getPaymentId();

//            Grava este pagamento
            $pgtoObj    = new Pagamento();
            $pgtoObj->create([
                "ProofOfSale"       => $sale->getPayment()->getProofOfSale(),
                "Tid"               => $sale->getPayment()->getTid(),
                "AuthorizationCode" => $sale->getPayment()->getAuthorizationCode(),
                "PaymentId"         => $sale->getPayment()->getPaymentId(),
                "Type"              => $sale->getPayment()->getType(),
                "Amount"            => $sale->getPayment()->getAmount(),
                "Status"            => $sale->getPayment()->getStatus(),
                "ReturnCode"        => $sale->getPayment()->getReturnCode(),
                "ReturnMessage"     => $sale->getPayment()->getReturnMessage(),
                "imagemComprovante" => null
            ]);

            // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
            $sale       = (new CieloEcommerce($this->Merchant, $this->Environment))->captureSale($paymentId, $this->Amount, 0);
            dd($sale);

            // E também podemos fazer seu cancelamento, se for o caso
            //$sale       = (new CieloEcommerce($this->Merchant, $this->Environment))->cancelSale($paymentId, $this->Amount);
        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error      = $e->getCieloError();
            dump('ERRO CREDITO:');
            dump($error);
            dd($e);
        }
    }

    /**
     * Método gera uma Venda com Cartão de Débito
     * através dos dados informados
     *
     * @param $cdSeguranca
     * @param $bandeira
     * @param $dtVencimento (mm-YYYY)
     * @param $nmrCartao
     * @param $nomeCartao
     */
    public function DebitPayment($codSeguranca, $dtVencimento, $nmrCartao, $nomeCartao)
    {

//        Aceitos
//        MASTERCARD	        VISA
//        Bradesco	        Bradesco
//        Banco do Brasil	    Banco do Brasil
//        Santander	        Santander
//        Itaú	            Itaú
//        CitiBank	        CitiBank
//        BRB	                N/A
//        Caixa	            N/A
//        BancooB	            N/A


        // Defina a URL de retorno para que o cliente possa voltar para a loja
        // após a autenticação do cartão
//        Url de retorno do lojista. URL para onde o comprador vai ser redirecionado no final do fluxo.
        $this->Payment->setReturnUrl(route('cieloRetorno'));

        // Crie uma instância de Debit Card utilizando os dados de teste
        // esses dados estão disponíveis no manual de integração
        $this->Payment->debitCard($codSeguranca, $this->Bandeira)//Código de segurança impresso no verso do cartão
        ->setExpirationDate($dtVencimento)//Data de validade impresso no cartão
        ->setCardNumber($nmrCartao)//Número do Cartão do Comprador
        ->setHolder($nomeCartao);//Nome do Comprador impresso no cartão

        // Crie o pagamento na Cielo
        try {
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale = (new CieloEcommerce($this->Merchant, $this->Environment))->createSale($this->Sale);
            dump($sale);

            // Com a venda criada na Cielo, já temos o ID do pagamento, TID e demais
            // dados retornados pela Cielo
            $paymentId = $this->Sale->getPayment()->getPaymentId();

//            Grava este pagamento
            $pgtoObj    = new Pagamento();
            $pgtoObj->create([
                "ProofOfSale"       => $sale->getPayment()->getProofOfSale(),
                "Tid"               => $sale->getPayment()->getTid(),
                "AuthorizationCode" => $sale->getPayment()->getAuthorizationCode(),
                "PaymentId"         => $sale->getPayment()->getPaymentId(),
                "Type"              => $sale->getPayment()->getType(),
                "Amount"            => $sale->getPayment()->getAmount(),
                "Status"            => $sale->getPayment()->getStatus(),
                "ReturnCode"        => $sale->getPayment()->getReturnCode(),
                "ReturnMessage"     => $sale->getPayment()->getReturnMessage(),
                "imagemComprovante" => null
            ]);

            // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
            $sale       = (new CieloEcommerce($this->Merchant, $this->Environment))->captureSale($paymentId, $this->Amount, 0);
            dd($sale);

            // Utilize a URL de autenticação para redirecionar o cliente ao ambiente
            // de autenticação do emissor do cartão
            $authenticationUrl = $this->Sale->getPayment()->getAuthenticationUrl();
            dd($authenticationUrl);

//            Grava esse pagamento
//            $pgtoObj    = new Pagamento();
//            $pgtoObj->create([
//                "ProofOfSale"       => $sale->getPayment()->getProofOfSale(),
//                "Tid"               => $sale->getPayment()->getTid(),
//                "AuthorizationCode" => $sale->getPayment()->getAuthorizationCode(),
//                "PaymentId"         => $sale->getPayment()->getPaymentId(),
//                "Type"              => $sale->getPayment()->getType(),
//                "Amount"            => $sale->getPayment()->getAmount(),
//                "Status"            => $sale->getPayment()->getStatus(),
//                "ReturnCode"        => $sale->getPayment()->getReturnCode(),
//                "ReturnMessage"     => $sale->getPayment()->getReturnMessage(),
//                "imagemComprovante" => null
//            ]);

//            URL para qual o Lojista deve redirecionar o Cliente para o fluxo de Débito.


//            Depois ele é redirecionado para a URL de retorno

        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();
            dump('ERRO DEBITO:');
            dump($error);
            dd($e);
        }
    }

    public function ETransfer($provedor)
    {
        switch ($provedor){
            case 'brasil':
                $provedor   = Payment::PROVIDER_BANCO_DO_BRASIL;
                break;
            case 'bradesco':
                $provedor   = Payment::PROVIDER_BRADESCO;
                break;
        }

        $this->Payment->setProvider($provedor);

        // Crie o pagamento na Cielo
        try {
            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $sale = (new CieloEcommerce($this->Merchant, $this->Environment))->createSale($this->Sale);

            // Com a venda criada na Cielo, já temos o ID do pagamento, TID e demais
            // dados retornados pela Cielo
            $paymentId = $this->Sale->getPayment()->getPaymentId();



        } catch (CieloRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();
        }
    }

    public function checkout()
    {
        $pedido = $this->Pedido;

        $sendArray['OrderNumber'] = $pedido->id;
        //$sendArray['SoftDescriptor'] = '';//Loja_ABC_1234

        if ($pedido->resgate) {
            $sendArray['Cart']['Discount']['Type'] = 'Amount';
            $sendArray['Cart']['Discount']['Value'] = number_format((float)$pedido->resgate->valorResgate*100, 0, '.', '');
        } else {

            if ($pedido->cupons) {}else{}

            $sendArray['Cart']['Discount']['Type'] = 'Percent';
            $sendArray['Cart']['Discount']['Value'] = 00;
        }

        $sendArray['Cart']['Items'] = array();
        foreach ($pedido->produtos as $produto) {

            $itens['Name'] = $produto->nome;
            $itens['Description'] = '';
            $itens['UnitPrice'] = 0;

            if ($produto->categoria->layout == 'Combo'){
                $itens['UnitPrice'] = $produto->preco;
                $pedProd        = $pedido->pedProds->find( $produto->pivot->id );

                foreach ($pedProd->produtosCombo as $prodCombo) {
                    $itens['Description']  = $itens['Description'].' / '.$prodCombo->nome;
//                    $itens['Name']  = $itens['Name'].' / '.;

                    $Ped_prod_prod_multiplo = new Ped_prod_prod_multiplo();
                    $prodMult = $Ped_prod_prod_multiplo->find($prodCombo->pivot->id);
                    if ($prodCombo->categoria->layout == 'Pizza') {
                        foreach ($prodMult->produtos as $sabor) {
                            $itens['Description']  = $itens['Description'].' / '.$sabor->nome;
                        }
                    }

                    if ( $prodMult->multiplos ) {
                        foreach ( $prodMult->multiplos as $ingrediente ){
                            $itens['Description']  = $itens['Description'].', '.$ingrediente->nome;
//                Acumula valores de SubIngredientes
                            if ( $ingrediente->pivot->valor > 0 ){
                                $itens['UnitPrice'] = $itens['UnitPrice'] + $ingrediente->pivot->valor;
                            }elseif ( $ingrediente->preco > 0 ){
                                $itens['UnitPrice'] = $itens['UnitPrice'] + $ingrediente->preco;
                            }
                        }

//                        dd($prodMult->multiplos);
                    }
                }
            }else {

                if ($produto->categoria->layout == 'Pizza') {
                    $pedProd = $pedido->pedProds->find($produto->pivot->id);
                    $saboresVal = $produto->preco;
                    $saboresSize = $pedProd->combinacoes->count() + 1;

                    foreach ($pedProd->combinacoes as $sabor) {
                        $itens['Name'] = $itens['Name'] . ' / ' . $sabor->nome;
//                Acumula valores de sabores
                        $saboresVal = $saboresVal + $sabor->pivot->valor;
                    }
                    foreach ($pedProd->ingredientes as $ingProdPed) {
//                    Caso tenha valor apresenta nome sub ingrediente
                        if ($ingProdPed->valor > 0) {
                            $itens['Description'] = $itens['Description'] . ' / ' . $ingProdPed->ingrediente->nome;
//                        $itens['Name']  = $itens['Name'].' / '.$ingProdPed->ingrediente->nome;
                        }
//                Acumula valores de SubIngredientes
                        $itens['UnitPrice'] = $itens['UnitPrice'] + ($ingProdPed->valor * $ingProdPed->quantidade);
                    }

                    $itens['UnitPrice'] = $itens['UnitPrice'] +
                        ($produto->pivot->quantidade * ($saboresVal / $saboresSize));

                } elseif ($produto->pivot) {
                    $itens['UnitPrice'] = $produto->preco;
                    foreach ($pedido->pedProds->find($produto->pivot->id)->multiplos as $ingMult) {
                        $itens['Description'] = $itens['Description'] . ', ' . $ingMult->ingrediente->nome;
//                Acumula valores de SubIngredientes
                        if ($ingMult->valor > 0) {
                            $itens['UnitPrice'] = $itens['UnitPrice'] + $ingMult->valor;
                        } elseif ($ingMult->ingrediente->preco > 0) {
                            $itens['UnitPrice'] = $itens['UnitPrice'] + $ingMult->ingrediente->preco;
                        }
                    }
                }
            }

//            Trata relação minimo de unidade com preço de minimo
            if ( $produto->minimo_unidade && $produto->minimo_unidade != 1 ){
                $itens['UnitPrice'] = $itens['UnitPrice'] / $produto->minimo_unidade;
            }
//            Converte para centavos
            $itens['UnitPrice']     = number_format((float)$itens['UnitPrice']*100., 0, '.', '');
            $itens['Quantity']      = $produto->pivot->quantidade;
            $itens['Type']          = 'Asset';
            //$itens['Sku']           = 'AAA';
            $itens['Weight']        = null;
            array_push($sendArray['Cart']['Items'], $itens);
        }

        if($pedido->enderecoEntrega) {
            $sendArray['Shipping']['SourceZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->enderecoEntrega->CEP);
            $sendArray['Shipping']['TargetZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->enderecoEntrega->CEP);
            $sendArray['Shipping']['Type']                  = 'FixedAmount';//Free em caso de cupom
            $sendArray['Shipping']['Services']              = [];
//        Foreach
            $taxa   = number_format((float)$pedido->enderecoEntrega->cep()->taxa_entrega*100., 0, '.', '');
            array_push($sendArray['Shipping']['Services'],[
                'Name'          => 'Motoboy',
                'Price'         => $taxa,
                'Deadline'      => 1, // Prazo Entrega em dias
                'Carrier'       => null
            ]);
//        Endforeach
            $sendArray['Shipping']['Address']['Street']     = $pedido->enderecoEntrega->Logradouro;
            $sendArray['Shipping']['Address']['Number']     = '1';
            $sendArray['Shipping']['Address']['Complement'] = $pedido->enderecoEntrega->Referencia;
            $sendArray['Shipping']['Address']['District']   = $pedido->enderecoEntrega->Bairro;
            $sendArray['Shipping']['Address']['City']       = $pedido->enderecoEntrega->Cidade;
            $sendArray['Shipping']['Address']['State']      = 'DF';//$pedido->enderecoEntrega->UFs_idUFs;
        }else{
            $sendArray['Shipping']['SourceZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->loja->CEP);
            $sendArray['Shipping']['TargetZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->loja->CEP);
            $sendArray['Shipping']['Type']                  = 'WithoutShippingPickUp';

            $sendArray['Shipping']['Address']['Street']     = 'Retirada na Loja';
            $sendArray['Shipping']['Address']['Number']     = '1';
            $sendArray['Shipping']['Address']['Complement'] = $pedido->loja->Logradouro;
            $sendArray['Shipping']['Address']['District']   = $pedido->loja->Bairro;
            $sendArray['Shipping']['Address']['City']       = $pedido->loja->Cidade;
            $sendArray['Shipping']['Address']['State']      = 'DF';
        }

        $sendArray['Payment']['BoletoDiscount']             = 0;
        $sendArray['Payment']['DebitDiscount']              = 0;
        $sendArray['Payment']['Installments']               = null;
        $sendArray['Payment']['MaxNumberOfInstallments']    = null;

        $sendArray['Customer']['Identity']  = preg_replace('/[^A-Za-z0-9]/', '', $pedido->cliente->CPF);
        $sendArray['Customer']['FullName']  = $pedido->cliente->nome;
        $sendArray['Customer']['Email']     = $pedido->cliente->email;
        $sendArray['Customer']['Phone']     = preg_replace('/[^A-Za-z0-9]/', '', $pedido->cliente->phone);

        $sendArray['Options']['AntifraudEnabled']   = true;
        $sendArray['Options']['ReturnUrl']          = route('cieloRetorno');

        $sendArray['Settings']  = null;

        $url                    = 'https://cieloecommerce.cielo.com.br/api/public/v1/orders';

        $response               = $this->sendRequest('POST', $url, $sendArray);

//        Gera Novo Pagamento
        $pagamentoObj           = new Pagamento();
        $pagamentoObj->create([
            'Pedidos_idPedidos'     => $pedido->id,
            'imagemComprovante'     => $response->settings->checkoutUrl
        ]);

//        Aí redireciono ele para a página de Checkout
        return $response;

//        Depois que eleclica em Finalizar com a Cielo
//        volta para minha página de retorno que é um método aqui

//        Resultado/Status da transação É retornado apenas via Notificação

//        BACKOFFICE    Na tela de pedidos, dentro de cada transação,
//                      há a opção de reenvio do POST de mudança de status

//        Tenho que ficar verificando o status do pagamento se for JSON
//        disparo um evento caso seja notificação POST



    }

    public function checkoutReact()
    {
        $pedido = $this->Pedido;

        $sendArray['OrderNumber'] = $pedido->id;
        //$sendArray['SoftDescriptor'] = '';//Loja_ABC_1234

        if ($pedido->resgate) {
            $sendArray['Cart']['Discount']['Type'] = 'Amount';
            $sendArray['Cart']['Discount']['Value'] = number_format((float)$pedido->resgate->valorResgate*100, 0, '.', '');
        } else {

            if ($pedido->cupons) {}else{}

            $sendArray['Cart']['Discount']['Type'] = 'Percent';
            $sendArray['Cart']['Discount']['Value'] = 00;
        }

        $sendArray['Cart']['Items'] = array();
        foreach ($pedido->pedProds as $pedProd) {

            $itens['Name'] = $pedProd->produto->nome;
            $itens['Description'] = '';
            $itens['UnitPrice'] = $pedProd->valor;

            foreach( $pedProd->pedProdMultiplo as $subProd ) {
                $itens['Description'] = $itens['Description'].$subProd->ProdutoMultiplo->nome . ' : ' . $subProd->subProduto->nome.' ';

                foreach( $subProd->combinacoes as $combinacao ){
                    $itens['Description'] = $itens['Description'] . ' / ' . $combinacao->nome.' ';
                }

                foreach( $subProd->multiplos as $multiplo ){
                    $itens['Description'] = $itens['Description'].$multiplo->multiplo->nome . ' : ' . $multiplo->ingrediente->nome.' ';
                }
            }

            foreach( $pedProd->combinacoes as $combinacao ){
                $itens['Name'] = $itens['Name'] . ' / ' . $combinacao->nome;
            }

            foreach( $pedProd->multiplos as $multiplo ){
                $itens['Description'] = $itens['Description'].$multiplo->multiplo->nome . ' : ' . $multiplo->ingrediente->nome . ' ';
            }

//            Converte para centavos
            $itens['UnitPrice']     = number_format((float)$itens['UnitPrice']*100., 0, '.', '');
            $itens['Quantity']      = $pedProd->quantidade;
            $itens['Type']          = 'Asset';
            //$itens['Sku']           = 'AAA';
            $itens['Weight']        = null;
            array_push($sendArray['Cart']['Items'], $itens);
        }

        if($pedido->enderecoEntrega) {
            $sendArray['Shipping']['SourceZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->enderecoEntrega->CEP);
            $sendArray['Shipping']['TargetZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->enderecoEntrega->CEP);
            $sendArray['Shipping']['Type']                  = 'FixedAmount';//Free em caso de cupom
            $sendArray['Shipping']['Services']              = [];
//        Foreach
            $taxa   = number_format((float)$pedido->enderecoEntrega->cep()->taxa_entrega*100., 0, '.', '');
            array_push($sendArray['Shipping']['Services'],[
                'Name'          => 'Motoboy',
                'Price'         => $taxa,
                'Deadline'      => 1, // Prazo Entrega em dias
                'Carrier'       => null
            ]);
//        Endforeach
            $sendArray['Shipping']['Address']['Street']     = $pedido->enderecoEntrega->Logradouro;
            $sendArray['Shipping']['Address']['Number']     = '1';
            $sendArray['Shipping']['Address']['Complement'] = $pedido->enderecoEntrega->Referencia;
            $sendArray['Shipping']['Address']['District']   = $pedido->enderecoEntrega->Bairro;
            $sendArray['Shipping']['Address']['City']       = $pedido->enderecoEntrega->Cidade;
            $sendArray['Shipping']['Address']['State']      = 'DF';//$pedido->enderecoEntrega->UFs_idUFs;
        }else{
            $sendArray['Shipping']['SourceZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->loja->CEP);
            $sendArray['Shipping']['TargetZipCode']         = preg_replace('/[^A-Za-z0-9]/', '', $pedido->loja->CEP);
            $sendArray['Shipping']['Type']                  = 'WithoutShippingPickUp';

            $sendArray['Shipping']['Address']['Street']     = 'Retirada na Loja';
            $sendArray['Shipping']['Address']['Number']     = '1';
            $sendArray['Shipping']['Address']['Complement'] = $pedido->loja->Logradouro;
            $sendArray['Shipping']['Address']['District']   = $pedido->loja->Bairro;
            $sendArray['Shipping']['Address']['City']       = $pedido->loja->Cidade;
            $sendArray['Shipping']['Address']['State']      = 'DF';
        }

        $sendArray['Payment']['BoletoDiscount']             = 0;
        $sendArray['Payment']['DebitDiscount']              = 0;
        $sendArray['Payment']['Installments']               = null;
        $sendArray['Payment']['MaxNumberOfInstallments']    = null;

        $sendArray['Customer']['Identity']  = preg_replace('/[^A-Za-z0-9]/', '', $pedido->cliente->CPF);
        $sendArray['Customer']['FullName']  = $pedido->cliente->nome;
        $sendArray['Customer']['Email']     = $pedido->cliente->email;
        $sendArray['Customer']['Phone']     = preg_replace('/[^A-Za-z0-9]/', '', $pedido->cliente->phone);

        $sendArray['Options']['AntifraudEnabled']   = true;
        $sendArray['Options']['ReturnUrl']          = route('cieloRetorno');

        $sendArray['Settings']  = null;

        $url                    = 'https://cieloecommerce.cielo.com.br/api/public/v1/orders';

        $response               = $this->sendRequest('POST', $url, $sendArray);

//        Gera Novo Pagamento
        $pagamentoObj           = new Pagamento();
        $pagamentoObj->create([
            'Pedidos_idPedidos'     => $pedido->id,
            'imagemComprovante'     => $response->settings->checkoutUrl
        ]);

//        Aí redireciono ele para a página de Checkout
        return $response;

//        Depois que eleclica em Finalizar com a Cielo
//        volta para minha página de retorno que é um método aqui

//        Resultado/Status da transação É retornado apenas via Notificação

//        BACKOFFICE    Na tela de pedidos, dentro de cada transação,
//                      há a opção de reenvio do POST de mudança de status

//        Tenho que ficar verificando o status do pagamento se for JSON
//        disparo um evento caso seja notificação POST



    }

    /**
     * Recebe o retorno e grava em algum arquivo
     * no Diretório /public/CIELO
     *
     * @param Request $request
     */
    public function retorno(Request $request, Pagamento $pagamentoObj)
    {
        return redirect()->route('pedido.index');


        $dataNotify         = $request->except('_token');
        $pagamento          = $pagamentoObj->where('Pedidos_idPedidos', $dataNotify['order_number'])->first();

        $pagamento->update([
            "Tid"               => $dataNotify['tid'],
            "PaymentId"         => $dataNotify['checkout_cielo_order_number'],
            "Amount"            => $dataNotify['amount'],
            "Type"              => $dataNotify['payment_method_type'],
            "Status"            => $dataNotify['payment_status'],
//            "ProofOfSale"       => null,
//            "AuthorizationCode" => null,
//            "ReturnCode"        => null,
//            "ReturnMessage"     => null,
//            "imagemComprovante" => null
        ]);

//    checkout_cielo_order_number
//    amount
//    order_number
//    payment_method_type
//    payment_method_brand
//    payment_method_bank
//    payment_maskedcredicard
//    payment_installments
//    payment_antifrauderesult
//    payment_boletonumber
//    payment_boletoexpirationdate
//    payment_status
//    tid	TID
//    test_transaction
//    product_id
//    product_type
//    product_sku
//    product_max_number_of_installments
//    product_expiration_date
//    product_quantity
//    product_description

        $fileName           = public_path('CIELO/Retorno_'.Carbon::now().'.json');

        if ( file_exists($fileName) ){
            $oldFileInfo    = file_get_contents($fileName);
            $fileArray      = json_decode($oldFileInfo, true);

            //Calcula qual segundo do dia está
            $seconds        = 86400 - Carbon::now()->diffInSeconds(Carbon::tomorrow());

            $fileArray[$seconds] = $request->except('_token');

            $newFileInfo    = json_encode($fileArray);

            file_put_contents($fileName, $newFileInfo);
        }else{
            fopen($fileName, 'x+');

            //Calcula qual segundo do dia está
            $seconds        = 86400 - Carbon::now()->diffInSeconds(Carbon::tomorrow());

            $fileArray      = [
                $seconds => $request->except('_token')
            ];

            $newFileInfo    = json_encode($fileArray);

            file_put_contents($fileName, $newFileInfo);

        }

        return;
    }

    /**
     * Recebe a notificação e grava em algum arquivo
     * no Diretório /public/CIELO
     *
     * @param Request $request
     */
    public function notifica(Request $request, Pagamento $pagamentoObj)
    {
//
//        POST de NOTIFICAÇÃO PRECISA SER CAPTURADO
//        Esse POST possui todos os dados do pedido, incluindo o STATUS inicial da transação
//
//        $sale       = (new CieloEcommerce($this->Merchant, $this->Environment))->captureSale($paymentId, $this->Amount, 0);
//
//
//        Conteudo ESPERADO no RESPONSE de Notificação
//            checkout_cielo_order_number	Identificador único gerado pelo CHECKOUT CIELO
//            amount	                    Preço unitário do produto, em centavos (ex: R$ 1,00 = 100)
//            order_number	                Número do pedido enviado pela loja
//            created_date	                Data da criação do pedido - dd/MM/yyyy HH:mm:ss
//            customer_name	                Nome do consumidor. Se enviado, esse valor já vem preenchido na tela do CHECKOUT CIELO
//            customer_identity	            Identificação do consumidor (CPF ou CNPJ) Se enviado, esse valor já vem preenchido na tela do CHECKOUT CIELO
//            customer_email	            E-mail do consumidor. Se enviado, esse valor já vem preenchido na tela do CHECKOUT CIELO
//            customer_phone	            Telefone do consumidor. Se enviado, esse valor já vem preenchido na tela do CHECKOUT CIELO
//            discount_amount	            Valor do desconto fornecido (enviado somente se houver desconto)
//            shipping_type	                Modalidade de frete
//            shipping_name	                Nome do frete
//            shipping_price	            Valor do serviço de frete, em centavos (ex: R$ 10,00 = 1000)
//            shipping_address_zipcode	    CEP do endereço de entrega
//            shipping_address_district	    Bairro do endereço de entrega
//            shipping_address_city	        Cidade do endereço de entrega
//            shipping_address_state	    Estado de endereço de entrega
//            shipping_address_line1	    Endereço de entrega
//            shipping_address_line2	    Complemento do endereço de entrega
//            shipping_address_number	    Número do endereço de entrega
//            payment_method_type	        Cód. do tipo de meio de pagamento
//            payment_method_brand	        Bandeira (somente para transações com meio de pagamento cartão de crédito)
//            payment_method_bank	        Banco emissor (Para transações de Boleto e Débito Automático)
//            payment_maskedcredicard	    Cartão Mascarado (Somente para transações com meio de pagamento cartão de crédito)
//            payment_installments	        Número de parcelas
//            payment_antifrauderesult	    Status das transações de cartão de Crédito no Antifraude
//            payment_boletonumber	        número do boleto gerado
//            payment_boletoexpirationdate	Data de vencimento para transações realizadas com boleto bancário
//            payment_status	            Status da transação
//            tid	                        TID Cielo gerado no momento da autorização da transação
//            test_transaction	            Indica se a transação foi gerada com o Modo de teste ativado
//            product_id	                Identificador do Botão/Link de pagamento que gerou a transação
//            product_type	                Tipo de Botão que gerou o pedido (Ver tabela de ProductID)
//            product_sku	                Identificador do produto cadastro no link de pagamento
//            product_max_number_of_installments	Numero de parcelas liberado pelo lojistas para o link de pagamento
//            product_expiration_date	    Data de validade do botão/Link de pagamento
//            product_quantity	            Numero de transações restantes até que o link deixe de funcionar
//            product_description	        Descrição do link de pagamentos registrada pelo lojista


//        Payment Status
//            Vlr  Status       Meios de pagamentos                 Descrição
//            1	Pendente	    Para todos os meios de pagamento    Indica que o pagamento ainda está sendo processado; OBS: Boleto - Indica que o boleto não teve o status alterado pelo lojista
//            2	Pago	        Para todos os meios de pagamento	Transação capturada e o dinheiro será depositado em conta.
//            3	Negado	        Somente para Cartão Crédito	        Transação não autorizada pelo responsável do meio de pagamento
//            4	Expirado	    Cartões de Crédito e Boleto	        Transação deixa de ser válida para captura - 15 dias pós Autorização
//            5	Cancelado	    Para cartões de crédito	            Transação foi cancelada pelo lojista
//            6	Não Finalizado	Todos os meios de pagamento	        Pagamento esperando Status - Pode indicar erro ou falha de processamento. Entre em contato com o Suporte cielo
//            7	Autorizado	    somente para Cartão de Crédito	    Transação autorizada pelo emissor do cartão. Deve ser capturada para que o dinheiro seja depositado em conta
//            8	Chargeback	    somente para Cartão de Crédito	    Transação cancelada pelo consumidor junto ao emissor do cartão. O Dinheiro não será depositado em conta.

//        Payment_method_brand
//
//            VALOR	DESCRIÇÃO
//            1	Visa
//            2	Mastercad
//            3	AmericanExpress
//            4	Diners
//            5	Elo
//            6	Aura
//            7	JCB
//            8	Discover
//            9	Hipercard

//        Payment_method_bank
//
//            VALOR	DESCRIÇÃO
//            1	Banco do Brasil
//            2	Bradesco

        try{

            $dataNotify         = $request->except('_token');
            $pagamento          = $pagamentoObj->where('Pedidos_idPedidos', $dataNotify['order_number'])->first();

            $pagamento->update([
            "Tid"               => $dataNotify['tid'],
            "PaymentId"         => $dataNotify['checkout_cielo_order_number'],
            "Amount"            => $dataNotify['amount'],
            "Type"              => $dataNotify['payment_method_type'],
            "Status"            => $dataNotify['payment_status'],
//            "ProofOfSale"       => null,
//            "AuthorizationCode" => null,
//            "ReturnCode"        => null,
//            "ReturnMessage"     => null,
//            "imagemComprovante" => null
        ]);

        }catch (\Exception $exception ){

            $fileName = public_path('CIELO/ErroNotifica.json');

            fopen($fileName, 'x+');

            $fileArray = [
                Carbon::now()->toDateTimeString()   => [
                    'Request'   => $request->except('_token'),
                    'Codigo'    => $exception->getCode(),
                    'Arquivo'   => $exception->getFile(),
                    'Linha'     => $exception->getLine(),
                    'Mensagem'  => $exception->getMessage(),
                    'Trace'     => $exception->getTraceAsString(),
                ]
            ];

            $newFileInfo = json_encode($fileArray);

            file_put_contents($fileName, $newFileInfo);

        }

        return;
    }

    /**
     * Recebe a mudanca e grava em algum arquivo
     * no Diretório /public/CIELO
     *
     * @param Request $request
     */
    public function mudanca(Request $request)
    {
        try {

            $dataNotify     = $request->except('_token');
//            {
//                "MerchantId"        :"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
//               "MerchantOrderNumber":"9999",
//               "Url"                :"https://cieloecommerce.cielo.com.br/api/public/v1/orders/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx/9999"
//            }
            $pagamentoObj       = new Pagamento();
            $pagamento          = $pagamentoObj
                                ->where('Pedidos_idPedidos', $dataNotify['MerchantOrderNumber'])
                                ->first();
            $this->consulta( $pagamento->id );

        }catch (\Exception $exception ){

            $fileName = public_path('CIELO/ErroMudanca.json');

            fopen($fileName, 'x+');

            $fileArray = [
                Carbon::now()->toDateTimeString()   => [
                    'Request'   => $request->except('_token'),
                    'Codigo'    => $exception->getCode(),
                    'Arquivo'   => $exception->getFile(),
                    'Linha'     => $exception->getLine(),
                    'Mensagem'  => $exception->getMessage(),
                    'Trace'     => $exception->getTraceAsString(),
                ]
            ];

            $newFileInfo = json_encode($fileArray);

            file_put_contents($fileName, $newFileInfo);

        }

        return;
    }

    /**
     * Consulta o pagamento Junto a Cielo
     * baseado no Pagamento já registrado na Loja
     *
     * @param $idPagamento
     */
    public function consulta($idPagamento)
    {
        $pagamentoObj       = new Pagamento();
        $pagamento          = $pagamentoObj->find($idPagamento);

        if( $pagamento->returnCode == 200 ){
            return $pagamento;
        }else {

            try {

//            $cielo          = new CieloEcommerce($this->Merchant, $this->Environment);
//            dump($cielo);
//            $capture        = $cielo->getSale($pagamento->PaymentId);
////                ->captureSale($pagamento->PaymentId, $pagamento->Amount, 0);
//            dd($capture);

                $url        = 'https://cieloecommerce.cielo.com.br/api/public/v1/orders/'.$this->MerchantID . '/' . $pagamento->Pedidos_idPedidos;
                $response   = $this->sendRequest('GET', $url);
                $status     = statusPagamentoOnline($response);

                $pagamento->update([
                    "Tid"               => $response->tid,
                    "PaymentId"         => $response->checkout_cielo_order_number,
                    "Amount"            => $response->amount,
                    "Type"              => $status['type'],
                    "Status"            => $response->payment_status,
                    "ReturnCode"        => $status['returnCode'],
                    "ReturnMessage"     => $status['status'] . ' - ' . $status['description']
//                "ProofOfSale"       => null,
//                "AuthorizationCode" => null,
//                "imagemComprovante" => null
                ]);

                if ( $status['returnCode'] == 205 ){
                    $pedidoObj              = new \App\Models\Pedido();
                    $pedido                 = $pedidoObj->find($pagamento->Pedidos_idPedidos);
                    $pedido->update(['status' => 'Cancelado']);
                }

//                return $response;
                return $pagamento;


            } catch (CieloRequestException $e) {
                // Em caso de erros de integração, podemos tratar o erro aqui.
                // os códigos de erro estão todos disponíveis no manual de integração.
                return $e;//->getCieloError();
            }

        }

    }

    /**
     * Captura o pagamento Junto a Cielo
     * baseado no Pagamento já registrado na Loja
     *
     * @param $idPagamento
     */
    public function captura($idPagamento)
    {
        $pagamentoObj       = new Pagamento();
        $pagamento          = $pagamentoObj->find($idPagamento);

        try {

//            $cielo          = new CieloEcommerce($this->Merchant, $this->Environment);
//            dump($cielo);
//
//            $sale           = $cielo->getSale($pagamento->PaymentId);
//            dump($sale);
//
//            if ( $sale ){
//                $capture    = $sale->captureSale($pagamento->PaymentId, $pagamento->Amount, 0);
//                dump($capture);
//            }
//
////            $sale       = (new CieloEcommerce($this->Merchant, $this->Environment))->captureSale($paymentId, $this->Amount, 0);

            return $pagamento;


            } catch (CieloRequestException $e) {
                // Em caso de erros de integração, podemos tratar o erro aqui.
                // os códigos de erro estão todos disponíveis no manual de integração.
//                dump($e);
                return $e;//->getCieloError();
            }

    }

    /**
     * @param                        $method
     * @param                        $url
     * @param \JsonSerializable|null $content
     *
     * @return mixed
     *
     * @throws \Cielo\API30\Ecommerce\Request\CieloRequestException
     * @throws \RuntimeException
     */
    protected function sendRequest($method, $url, $content = null)
    {
        $headers = [
            //'Accept: application/json',
            //'Accept-Encoding: gzip',
            //'User-Agent: CieloEcommerce/3.0 PHP SDK',
            'MerchantId: ' . $this->MerchantID,
            //'MerchantKey: ' . $this->MerchantKEY,
            //'RequestId: ' . uniqid(),
            'Content-Type: application/json'
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if ($content !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($content));

            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response   = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            throw new \RuntimeException('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        return $this->readResponse($statusCode, $response);

        /*


        $options        = [
            "headers"   => ["MerchantId" => $this->MerchantID, "Content-Type" => "application/json"]
        ];

        $client     = new Client();

        $response   = $client->post($url, $options);

        return $this->readResponse(200, $response);*/
    }

    /**
     * @param $statusCode
     * @param $responseBody
     *
     * @return mixed
     *
     * @throws CieloRequestException
     */
    protected function readResponse($statusCode, $responseBody)
    {
        $unserialized = null;

        switch ($statusCode) {
            case 200:
            case 201:
                $unserialized = json_decode($responseBody);
                break;
            case 400:
                $exception = null;
                $response  = json_decode($responseBody);

                dump($response);
                foreach ($response as $error) {
                    $cieloError = new CieloError($error->Message, $error->Code);
                    $exception  = new CieloRequestException('Request Error', $statusCode, $exception);
                    $exception->setCieloError($cieloError);
                }

                throw $exception;
            case 404:
                throw new CieloRequestException('Resource not found', 404, null);
            case 403:
                throw new CieloRequestException('Forbidden', 403);
            default:
                throw new CieloRequestException($responseBody, $statusCode);
                throw new CieloRequestException('Unknown status', $statusCode);
        }

        return $unserialized;
    }

}
