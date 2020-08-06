<?php

namespace iFood\API\Ecommerce\Request;

/**
 * Class iFoodRequestException
 *
 * @package iFood\API\Ecommerce\Request
 */
class ifoodRequestException extends \Exception
{

    private $iFoodError;

    /**
     * iFoodRequestException constructor.
     *
     * @param string $message
     * @param int    $code
     * @param null   $previous
     */
    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getiFoodError()
    {
        return $this->iFoodError;
    }

    /**
     * @param ifoodError $iFoodError
     *
     * @return $this
     */
    public function setiFoodError(ifoodError $iFoodError)
    {
        $this->iFoodError = $iFoodError;

        return $this;
    }
}
