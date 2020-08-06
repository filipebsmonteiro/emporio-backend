<?php

namespace iFood\API\Ecommerce;

/**
 * Class Environment
 *
 * @package iFood\API\Ecommerce
 */
class Environment implements \iFood\API\Environment
{
    private $api;

    /**
     * Environment constructor.
     *
     * @param $api
     */
    private function __construct($api)
    {
        $this->api      = $api;
    }

    /**
     * @return Environment
     */
    public static function production()
    {
        $api      = 'https://pos-api.ifood.com.br/';

        return new Environment($api);
    }

    /**
     * Gets the environment's Api URL
     *
     * @return string the Api URL
     */
    public function getApiUrl()
    {
        return $this->api;
    }
}
