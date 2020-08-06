<?php

namespace iFood\API\Ecommerce;

/**
 * Class Sale
 *
 * @package iFood\API\Ecommerce
 */
class Sale implements \JsonSerializable
{
    private $id;
    private $reference;
    private $shortReference;
    private $createdAt;
    private $type;
    private $subTotal;
    private $totalPrice;
    private $deliveryFee;
    private $deliveryDateTime;

    private $payments;

    private $customer;

    private $items;

    private $deliveryAddress;

    /**
     * Sale constructor.
     *
     * @param null $merchantOrderId
     */
    public function __construct($merchantOrderId = null)
    {
        $this->setMerchantOrderId($merchantOrderId);
    }

    /**
     * @param $json
     *
     * @return Sale
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $sale = new Sale();
        $sale->populate($object);

        return $sale;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->id               = isset($data->id)              ? !!$data->id : null;
        $this->reference        = isset($data->reference)       ? !!$data->reference : null;
        $this->shortReference   = isset($data->shortReference)  ? !!$data->shortReference : null;
        $this->createdAt        = isset($data->createdAt)       ? !!$data->createdAt : null;
        $this->type             = isset($data->type)            ? !!$data->type : null;
        $this->subTotal         = isset($data->subTotal)        ? !!$data->subTotal : null;
        $this->totalPrice       = isset($data->totalPrice)      ? !!$data->totalPrice : null;
        $this->deliveryFee      = isset($data->deliveryFee)     ? !!$data->deliveryFee : null;
        $this->deliveryDateTime = isset($data->deliveryDateTime)? !!$data->deliveryDateTime : null;

        $dataProps = get_object_vars($data);

        if (isset($dataProps['customer'])) {
            $this->customer = new Customer();
            $this->customer->populate($data->customer);
        }

        if (isset($dataProps['payments'])) {
            foreach($data->payments as $payment){
                $auxPayment     = new Payment();
                $auxPayment->populate($payment);
                array_push($this->payments, $auxPayment);
            }
        }

        if (isset($dataProps['items'])) {
            $this->items = new Items();
            $this->items->populate($data->items);
        }

        if (isset($dataProps['deliveryAddress'])) {
            $this->deliveryAddress = new DeliveryAddress();
            $this->deliveryAddress->populate($data->deliveryAddress);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @param $name
     *
     * @return Customer
     */
    public function customer($id)
    {
        $customer = new Customer($id);

        $this->setCustomer($id);

        return $customer;
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
    public function getShortReference()
    {
        return $this->shortReference;
    }

    /**
     * @param mixed $shortReference
     */
    public function setShortReference($shortReference)
    {
        $this->shortReference = $shortReference;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param mixed $subTotal
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return mixed
     */
    public function getDeliveryFee()
    {
        return $this->deliveryFee;
    }

    /**
     * @param mixed $deliveryFee
     */
    public function setDeliveryFee($deliveryFee)
    {
        $this->deliveryFee = $deliveryFee;
    }

    /**
     * @return mixed
     */
    public function getDeliveryDateTime()
    {
        return $this->deliveryDateTime;
    }

    /**
     * @param mixed $deliveryDateTime
     */
    public function setDeliveryDateTime($deliveryDateTime)
    {
        $this->deliveryDateTime = $deliveryDateTime;
    }

    /**
     * @return mixed
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /*
     *
     */
    public function setPayment(array $payments)
    {
        $this->payments = $payments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     *
     * @return $this
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /*
     *
     */
    public function setDeliveryAddress(DeliveryAddress $deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }
}
