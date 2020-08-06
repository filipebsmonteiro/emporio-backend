<?php

namespace iFood\API\Ecommerce;

/**
 * Interface iFoodSerializable
 *
 * @package iFood\API\Ecommerce
 */
interface ifoodSerializable extends \JsonSerializable
{
    /**
     * @param \stdClass $data
     *
     * @return mixed
     */
    public function populate(\stdClass $data);
}
