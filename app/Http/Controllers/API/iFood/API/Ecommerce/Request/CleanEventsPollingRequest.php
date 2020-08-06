<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/02/2019
 * Time: 18:31
 */

namespace iFood\API\Ecommerce\Request;


use iFood\API\Ecommerce\Event;

class CleanEventsPollingRequest extends AbstractRequest
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
        $url = $this->environment->getApiUrl() . 'v1.0/events/acknowledgment';

        return $this->sendRequest('POST', $url, $param);
    }

    /**
     * @inheritdoc
     */
    protected function unserialize($json)
    {
        return Event::fromJson($json);
    }
}