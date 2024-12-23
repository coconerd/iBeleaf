<?php

namespace App\Exceptions;

use Exception;

class ShippingFeeCalculationException extends Exception
{
    private $responseData;

    public function __construct($message, $responseData = null)
    {
        parent::__construct($message);
        $this->responseData = $responseData;
    }

    public function getResponseData()
    {
        return $this->responseData;
    }
}