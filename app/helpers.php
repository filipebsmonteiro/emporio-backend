<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 30/06/2018
 * Time: 15:02
 */

if (! function_exists('display_ingredientes')) {
    function display_ingredientes($produto)
    {

        $retorno = '';

        if ( $produto->categoria->layout == "Pizza" ){

//        Escreve MULTIPLOS
            if (!$produto->categoria->multiplos->isEmpty()) {
                foreach ($produto->ingredientes as $ingrediente) {
                    if ( $ingrediente == $produto->ingredientes->last() ) {

//                        Ultimo Multiplo
                        $retorno = $retorno . $ingrediente->nome.'.';

                    } else {
                        $retorno = $retorno . $ingrediente->nome . ', ';
                    }
                }
            }

        } elseif ( $produto->categoria->layout == "Combo" ) {

//        Escreve Produtos MULTIPLOS
            if (!$produto->produtosMultiplos->isEmpty()) {
                foreach ($produto->produtosMultiplos as $multiplo) {
                    if ( $multiplo == $produto->produtosMultiplos->last() ) {

//                        Ultimo Multiplo
                        $retorno = $retorno . 'Escolha: ' . $multiplo->nome;

                    } else {
                        $retorno = $retorno . 'Escolha: ' . $multiplo->nome . ', ';
                    }
                }
            }

        }else{

//        Escreve MULTIPLOS
            if (!$produto->multiplos->isEmpty()) {
                foreach ($produto->multiplos as $multiplo) {
                    if ($multiplo == $produto->multiplos->last()) {

//                        Ultimo Multiplo
                        $retorno = $retorno . 'Escolha: ' . $multiplo->nome;

                    } else {
                        $retorno = $retorno . 'Escolha: ' . $multiplo->nome . ', ';
                    }
                }
            }

//        Escreve INGREDIENTES
            if (!$produto->ingredientes->isEmpty()) {
                foreach ($produto->ingredientes as $ingrediente) {
                    if (
                        $ingrediente->pivot->visibilidade == 'Essencial Visível' ||
                        $ingrediente->pivot->visibilidade == 'Ingrediente'
                    ) {

                        if ($ingrediente == $produto->ingredientes->first() && !empty($retorno)) {

//                    Primeiro elemento com multiplos existentes
                            $retorno = $retorno . ', ' . $ingrediente->nome . ', ';

                        } elseif ($ingrediente == $produto->ingredientes->last()) {

//                        Ultimo Elemento
                            $retorno = $retorno . $ingrediente->nome . '.';

                        } else {
                            $retorno = $retorno . $ingrediente->nome . ', ';
                        }

                    }
                }
            }

        }

        return $retorno;
    }
}

if (! function_exists('display_action')) {
    function display_action($produto, $target='Modal')
    {
        $retorno = '';

        if ( $produto->categoria->layout == "Pizza" ){

            $retorno    = "onclick=".$target."('" . route('modal.pizza', $produto->id) . "')";

        } elseif ( $produto->categoria->layout == "Combo" ) {

            $retorno    = "onclick=".$target."('" . route('modal.combo', $produto->id) . "')";

        }else{

//        Escreve MULTIPLOS
            if (!$produto->multiplos->isEmpty()) {

                $retorno    = "onclick=".$target."('" . route('modal.multiplo', $produto->id) . "')";

            }else{
                $retorno    = "onclick='addProduto(" . $produto . ")'";
            }

        }

        return $retorno;
    }
}

if (! function_exists('display_pedido_created')) {
    function display_pedido_date($data)
    {
        $data       = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data);
        $retorno    = $data->format('d').', ';
        switch ( $data->format('m') ){
            case 1:
                $retorno = $retorno.'Janeiro';
                break;
            case 2:
                $retorno = $retorno.'Fevereiro';
                break;
            case 3:
                $retorno = $retorno.'Março';
                break;
            case 4:
                $retorno = $retorno.'Abril';
                break;
            case 5:
                $retorno = $retorno.'Maio';
                break;
            case 6:
                $retorno = $retorno.'Junho';
                break;
            case 7:
                $retorno = $retorno.'Julho';
                break;
            case 8:
                $retorno = $retorno.'Agosto';
                break;
            case 9:
                $retorno = $retorno.'Setembro';
                break;
            case 10:
                $retorno = $retorno.'Outubro';
                break;
            case 11:
                $retorno = $retorno.'Novembro';
                break;
            case 12:
                $retorno = $retorno.'Dezembro';
                break;
        }

        $retorno = $retorno.' - '.$data->format('H:i');

        return $retorno;
    }
}

if (! function_exists('statusPagamentoOnline')) {
    function statusPagamentoOnline($pagamento)
    {
        if (
            !isset($pagamento->payment_method_type) ||
            $pagamento instanceof \Cielo\API30\Ecommerce\Request\CieloRequestException
        ){

            return [
                'type'                  => 'Nenhum',
                'status'                => 'Usuário não finalizou Compra.',
                'description'           => 'Problemas ao identificar o Pagamento Online!'
                ];
        }

        $retorno        = [];

        switch ($pagamento->payment_method_type){
            case 1:
                $retorno['type']    = 'Cartão de Crédito';
                break;
            case 2:
                $retorno['type']    = 'Boleto Bancário';
                break;
            case 3:
                $retorno['type']    = 'Débito Online';
                break;
            case 4:
                $retorno['type']    = 'Cartão de Débito';
                break;
        }

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

        switch ($pagamento->payment_status){
            case 1:
                $retorno['returnCode']  = 200;
                $retorno['status']      = 'Pendente';
                $retorno['description'] = 'Indica que o pagamento ainda está sendo processado; OBS: Boleto - Indica que o boleto não teve o status alterado pelo lojista';
                break;
            case 2:
                $retorno['returnCode']  = 200;
                $retorno['status']      = 'Pago';
                $retorno['description'] = 'Transação capturada e o dinheiro será depositado em conta.';
                break;
            case 3:
                $retorno['returnCode']  = 403;
                $retorno['status']      = 'Negado';
                $retorno['description'] = 'Transação não autorizada pelo responsável do meio de pagamento';
                break;
            case 4:
                $retorno['returnCode']  = 404;
                $retorno['status']      = 'Expirado';
                $retorno['description'] = 'Transação deixa de ser válida para captura - 15 dias pós Autorização';
                break;
            case 5:
                $retorno['returnCode']  = 405;
                $retorno['status']      = 'Cancelado';
                $retorno['description'] = 'Transação foi cancelada pelo lojista';
//                Canccela pedido
                break;
            case 6:
                $retorno['returnCode']  = 406;
                $retorno['status']      = 'Não Finalizado';
                $retorno['description'] = 'Pagamento esperando Status - Pode indicar erro ou falha de processamento. Entre em contato com o Suporte cielo';
                break;
            case 7:
                $retorno['returnCode']  = 200;
                $retorno['status']      = 'Autorizado';
                $retorno['description'] = 'Transação autorizada pelo emissor do cartão. Deve ser capturada para que o dinheiro seja depositado em conta';
                break;
            case 8:
                $retorno['returnCode']  = 408;
                $retorno['status']      = 'Chargeback';
                $retorno['description'] = 'Transação cancelada pelo consumidor junto ao emissor do cartão. O Dinheiro não será depositado em conta.';
                break;

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
        }
        return $retorno;
    }
}
