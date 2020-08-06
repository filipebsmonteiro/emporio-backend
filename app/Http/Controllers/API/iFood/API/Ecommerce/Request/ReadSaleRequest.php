<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/02/2019
 * Time: 18:31
 */

namespace iFood\API\Ecommerce\Request;


use iFood\API\Ecommerce\Event;
use iFood\API\Ecommerce\Sale;

class ReadSaleRequest extends AbstractRequest
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
     * @correlationId
     *
     * @inheritdoc
     */
    public function execute($correlationId)
    {
        $url = $this->environment->getApiUrl() . 'v1.0/orders/'.$correlationId;

        return $this->sendRequest('GET', $url);
    }

    /**
     * @inheritdoc
     */
    protected function unserialize($json)
    {
        return Sale::fromJson($json);
    }
}