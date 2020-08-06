<?php

namespace iFood\API;

/**
 * Class Merchant
 *
 * @package iFood\API
 */
class Merchant
{
    private $id;
    private $secret;
    private $username;
    private $password;

    /**
     * Merchant constructor.
     *
     * @param $id
     * @param $secret
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)//($id, $secret, $username, $password)
    {
//        Dados da Marca
//        $this->id       = $id;
//        $this->secret   = $secret;
        $this->id       = env('IFOOD_MERCHANT_ID');
        $this->secret   = env('IFOOD_MERCHANT_SECRET');

//        Dados do Usuario
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
