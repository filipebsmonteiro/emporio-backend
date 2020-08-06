<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/02/2019
 * Time: 18:31
 */

namespace iFood\API\Ecommerce\Request;


use iFood\API\Ecommerce\Event;

class EventsPollingRequest extends AbstractRequest
{

    private $environment;
    /** @var Merchant $merchant */
    private $merchant;

    /**
     * CreateCardTokenRequestHandler constructor.
     *
     * @param Merchant $merchant
     * @param Environment $environment
     */
    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->merchant = $merchant;
        $this->environment = $environment;
    }

    /**
     * @inheritdoc
     */
    public function execute($param)
    {
        $url = $this->environment->getApiUrl() . 'v1.0/events%3Apolling';

        return $this->sendRequest('GET', $url);

//        PLACED      - Indica um pedido foi colocado no sistema.
//        CONFIRMED   - Indica um pedido confirmado.
//        INTEGRATED  - Indica um pedido que foi recebido pelo e-PDV.
//        CANCELLED   - Indica um pedido que foi cancelado.
//        DISPATCHED  - Indica um pedido que foi despachado ao cliente.
//        DELIVERED   - Indica um pedido que foi entregue.
//        CONCLUDED   - Indica um pedido que foi concluído (Em até duas horas do fluxo normal)*.
    }

    /**
     * @inheritdoc
     */
    protected function unserialize($json)
    {
        return Event::fromJson($json);
    }
}