<?php

namespace iFood\API\Ecommerce\Request;

/**
 * Class iFoodError
 *
 * @package iFood\API\Ecommerce\Request
 */
class ifoodError
{

    private $code;

    private $message;

    private $description;

    /**
     * iFoodError constructor.
     *
     * @param $message
     * @param $code
     */
    public function __construct($code, $message, $description=null)
    {
        $this->setMessage($message);
        $this->setCode($code);
        $this->setDescription($description);
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;

        return $this;
    }


}
