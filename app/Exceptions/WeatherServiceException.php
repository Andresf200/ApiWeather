<?php

namespace App\Exceptions;

use Exception;

/**
 * Clase WeatherServiceException
 *
 * Esta clase es una excepción personalizada que se lanza cuando ocurre un error en el servicio de clima.
 *
 * @package App\Exceptions
 */
class WeatherServiceException extends Exception
{
    /**
     * La URL de la solicitud que causó la excepción.
     *
     * @var string
    */
    private $url;

    /**
     * Crea una nueva instancia de la excepción.
     *
     * @param string $message El mensaje de la excepción.
     * @param int $code El código de la excepción.
     * @param Exception $previous La excepción anterior utilizada para el encadenamiento de excepciones.
     * @param string $url La URL de la solicitud que causó la excepción.
    */
    public function __construct($message = "", $code = 0, Exception $previous = null, $url = "")
    {
        parent::__construct($message, $code, $previous);
        $this->url = $url;
    }

    /**
     * Obtiene la URL de la solicitud que causó la excepción.
     *
     * @return string La URL de la solicitud que causó la excepción.
    */
    public function getUrl()
    {
        return $this->url;
    }
}
