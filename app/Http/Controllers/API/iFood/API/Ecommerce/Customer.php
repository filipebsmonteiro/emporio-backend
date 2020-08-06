<?php

namespace iFood\API\Ecommerce;

/**
 * Class Customer
 *
 * @package iFood\API\Ecommerce
 */
class Customer implements \JsonSerializable
{

    private $id;

    private $name;

    private $taxPayerIdentificationNumber;

    private $phone;

    /**
     * @param $json
     *
     * @return Customer
     */
    public static function fromJson($json)
    {
        $customer = new Customer();
        $customer->populate(json_decode($json));

        return $customer;
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
        $this->id           = isset($data->id)    ? $data->id : null;
        $this->name         = isset($data->name)  ? $data->name : null;
        $this->taxPayerIdentificationNumber = isset($data->taxPayerIdentificationNumber) ? $data->taxPayerIdentificationNumber : null;
        $this->phone        = isset($data->Phone) ? $data->phone: null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->name = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxPayerIdentificationNumber()
    {
        return $this->taxPayerIdentificationNumber;
    }

    /**
     * @param mixed $taxPayerIdentificationNumber
     */
    public function setTaxPayerIdentificationNumber($taxPayerIdentificationNumber)
    {
        $this->taxPayerIdentificationNumber = $taxPayerIdentificationNumber;
    }


}
