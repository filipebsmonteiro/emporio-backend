<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/02/2019
 * Time: 18:35
 */

namespace iFood\API\Ecommerce;


class Token
{
    private $access_token;
    private $token_type;
    private $expires_in;
    private $scope;

    /**
     * @param $json
     *
     * @return Token
     */
    public static function fromJson($json)
    {
        $token = new Token();
        $token->populate(json_decode($json));

        return $token;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->access_token = isset($data->access_token)    ? $data->access_token : null;
        $this->token_type   = isset($data->token_type)      ? $data->token_type : null;
        $this->expires_in   = isset($data->expires_in)      ? $data->expires_in : null;
        $this->scope        = isset($data->scope)           ? $data->scope : null;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     */
    public function setAccessToken($access_token): void
    {
        $this->access_token = $access_token;
    }

    /**
     * @return mixed
     */
    public function getTokenType()
    {
        return $this->token_type;
    }

    /**
     * @param mixed $token_type
     */
    public function setTokenType($token_type): void
    {
        $this->token_type = $token_type;
    }

    /**
     * @return mixed
     */
    public function getExpiresIn()
    {
        return $this->expires_in;
    }

    /**
     * @param mixed $expires_in
     */
    public function setExpiresIn($expires_in): void
    {
        $this->expires_in = $expires_in;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope): void
    {
        $this->scope = $scope;
    }


}