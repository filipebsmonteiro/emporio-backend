<?php

namespace Cielo\API30\Ecommerce\Request;

use iFood\API\Ecommerce\Environment;
use iFood\API\Ecommerce\Event;
use iFood\API\Ecommerce\Token;
use iFood\API\Merchant;

/**
 * Class CreateCardTokenRequestHandler
 *
 * @package AppBundle\Handler\Cielo
 */
class TokenizeMerchantRequest extends AbstractRequest
{

    private $environment;
    /** @var Merchant $merchant */
    private $merchant;

    /**
     * CreateTokenRequestHandler constructor.
     *
     * @param Merchant    $merchant
     * @param Environment $environment
     */
    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->merchant    = $merchant;
        $this->environment = $environment;
    }

    /**
     * @inheritdoc
     */
    public function execute($param)
    {

        $url = $this->environment->getApiUrl() . 'oauth/token';

        $params                     = [];
        $params['client_id']        = $this->merchant->getId();
        $params['client_secret']    = $this->merchant->getPassword();
        $params['grant_type']       = 'password';
        $params['username']         = $this->merchant->getUsername();
        $params['password']         = $this->merchant->getPassword();

        $url .= '?' . http_build_query($params);

        return $this->sendRequest('POST', $url);
    }

    /**
     * @inheritdoc
     */
    protected function unserialize($json)
    {
        return Token::fromJson($json);
    }
}
