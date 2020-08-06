<?php

namespace iFood\API\Ecommerce;

/**
 * Class Payment
 *
 * @package iFood\API\Ecommerce
 */
class Payment implements \JsonSerializable
{

    private $name;

    private $code;

    private $value;

    private $prepaid;

    private $externalCode;

    private $collector;

    private $issuer;

    /**
     * @param $json
     *
     * @return Payment
     */
    public static function fromJson($json)
    {
        $payment = new Payment();
        $payment->populate(json_decode($json));

        return $payment;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {

        $this->name         = isset($data->name)            ? $data->name : null;
        $this->code         = isset($data->code)            ? $data->code : null;
        $this->value        = isset($data->value)           ? $data->value : null;
        $this->prepaid      = isset($data->prepaid)         ? !!$data->prepaid : false;
        $this->externalCode = isset($data->externalCode)    ? !!$data->externalCode : null;
        $this->collector    = isset($data->collector)       ? !!$data->collector : null;
        $this->issuer       = isset($data->issuer)          ? !!$data->issuer : null;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getPrepaid()
    {
        return $this->prepaid;
    }

    /**
     * @param mixed $prepaid
     */
    public function setPrepaid($prepaid)
    {
        $this->prepaid = $prepaid;
    }

    /**
     * @return mixed
     */
    public function getExternalCode()
    {
        return $this->externalCode;
    }

    /**
     * @param mixed $externalCode
     */
    public function setExternalCode($externalCode)
    {
        $this->externalCode = $externalCode;
    }

    /**
     * @return mixed
     */
    public function getCollector()
    {
        return $this->collector;
    }

    /**
     * @param mixed $collector
     */
    public function setCollector($collector)
    {
        $this->collector = $collector;
    }

    /**
     * @return mixed
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * @param mixed $issuer
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
    }


}
