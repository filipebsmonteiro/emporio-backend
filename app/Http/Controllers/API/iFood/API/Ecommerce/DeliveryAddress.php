<?php

namespace iFood\API\Ecommerce;

/**
 * Class Address
 *
 * @package iFood\API\Ecommerce
 */
class DeliveryAddress implements ifoodSerializable
{

    private $formattedAddress;

    private $country;

    private $state;

    private $city;

    private $coordinates;

    private $neighborhood;

    private $streetName;

    private $streetNumber;

    private $postalCode;

    private $reference;

    private $complement;

    /**
     * @param $json
     *
     * @return DeliveryAddress
     */
    public static function fromJson($json)
    {
        $deliveryAddress = new DeliveryAddress();
        $deliveryAddress->populate(json_decode($json));

        return $deliveryAddress;
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
        $this->formattedAddress = isset($data->formattedAddress)? $data->formattedAddress : null;
        $this->country          = isset($data->country)         ? $data->country : null;
        $this->state            = isset($data->state)           ? $data->state : null;
        $this->city             = isset($data->city)            ? $data->city : null;
        $this->coordinates      = isset($data->coordinates)     ? $data->coordinates : [];
        $this->neighborhood     = isset($data->neighborhood)    ? $data->neighborhood : null;
        $this->streetName       = isset($data->streetName)      ? $data->streetName : null;
        $this->streetNumber     = isset($data->streetNumber)    ? $data->streetNumber : null;
        $this->postalCode       = isset($data->postalCode)      ? $data->postalCode : null;
        $this->reference        = isset($data->reference)       ? $data->reference : null;
        $this->complement       = isset($data->complement)      ? $data->complement : null;
    }

    /**
     * @return mixed
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * @param mixed $formattedAddress
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param mixed $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @return mixed
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * @param mixed $neighborhood
     */
    public function setNeighborhood($neighborhood)
    {
        $this->neighborhood = $neighborhood;
    }

    /**
     * @return mixed
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * @param mixed $streetName
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;
    }

    /**
     * @return mixed
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @param mixed $streetNumber
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @param mixed $complement
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;
    }


}
