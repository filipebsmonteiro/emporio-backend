<?php

namespace iFood\API\Ecommerce\Request;

use iFood\API\Ecommerce\Payment;
use iFood\API\Ecommerce\Sale;
use iFood\API\Environment;
use iFood\API\Merchant;

/**
 * Class UpdateSaleRequest
 *
 * @package iFood\API\Ecommerce\Request
 */
class UpdateSaleRequest extends AbstractRequest
{

    private $environment;
    /** @var Merchant $merchant */
    private $merchant;
    private $reference;

    /**
     * UpdateSaleRequest constructor.
     *
     * @param Merchant    $type
     * @param Merchant    $merchant
     * @param Environment $environment
     */
    public function __construct(Merchant $merchant, Environment $environment, $reference)
    {
        parent::__construct($merchant);

        $this->merchant     = $merchant;
        $this->environment  = $environment;
        $this->reference    = $reference;
    }

    /**
     * @param $status
     *
     * @return null
     * @throws \iFood\API\Ecommerce\Request\ifoodRequestException
     * @throws \RuntimeException
     */
    public function execute($status)
    {

        $url    = $this->environment->getApiUrl() . 'v1.0/orders/'.$this->reference.'/statuses/'.$status;

//        return $this->sendRequest('POST', $url, $params);

        return $this->sendRequest('POST', $url);
    }

    /**
     * @param $json
     *
     * @return Payment
     */
    protected function unserialize($json)
    {
        return Sale::fromJson($json);
    }
}
