<?php

namespace App\Exceptions;

use Exception;


class ForecastServiceException extends Exception
{
    private $url;

    public function __construct($message = "", $code = 0, Exception $previous = null, $url = "")
    {
        parent::__construct($message, $code, $previous);
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
