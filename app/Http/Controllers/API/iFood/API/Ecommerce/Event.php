<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/02/2019
 * Time: 18:06
 */

namespace iFood\API\Ecommerce;


class Event implements \JsonSerializable
{
    private $code;

    private $correlationId;

    private $createdAt;

    private $id;

    /**
     * @param $json
     *
     * @return Event
     */
    public static function fromJson($json)
    {
        $event = new Event();
        $event->populate(json_decode($json));

        return $event;
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
        $this->code             = isset($data->code)            ? $data->code : null;
        $this->correlationId    = isset($data->correlationId)   ? $data->correlationId : null;
        $this->createdAt        = isset($data->createdAt)       ? $data->createdAt : null;
        $this->id               = isset($data->id)              ? $data->id : null;
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
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    /**
     * @param mixed $correlationId
     */
    public function setCorrelationId($correlationId): void
    {
        $this->correlationId = $correlationId;
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
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
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
    public function setId($id): void
    {
        $this->id = $id;
    }

}