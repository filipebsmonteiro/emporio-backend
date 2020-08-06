<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/02/2019
 * Time: 16:38
 */

namespace iFood\API\Ecommerce;


use iFood\API\SubItems;

class Items implements \JsonSerializable
{
    private $items;

    /**
     * @param $json
     *
     * @return Items
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $items = new Items();
        $items->populate($object);

        return $items;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->items    = isset($data->items) ? $data->items : [];
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
}