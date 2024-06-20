<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Exceptions\WeatherServiceException;

/**
 * Clase WeatherService
 *
 * Esta clase es un servicio que proporciona métodos para obtener datos del clima de la API de OpenWeatherMap.
 *
 * @package App\Services
 */
class WeatherService
{
    /**
     * La URL de la API de OpenWeatherMap.
     *
     * @var string
    */
    private const API_URL = 'https://api.openweathermap.org/data/2.5/weather';

    /**
     * El cliente HTTP utilizado para hacer solicitudes a la API.
     *
     * @var Client
     */
    private $client;

    /**
     * La clave de la API de OpenWeatherMap.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Crea una nueva instancia del servicio.
     *
     * @param Client $client El cliente HTTP.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('services.weather.api_key');
    }

    /**
     * Obtiene los datos del clima para la ubicación dada.
     *
     * @param string $location La ubicación para la cual obtener los datos del clima.
     * @param string $units Las unidades de medida para la temperatura.
     * @return array Los datos del clima.
     * @throws WeatherServiceException Si ocurre un error al obtener los datos del clima.
    */
    public function getWeather(string $location, string $units)
    {
        try {
            $response = $this->client->get(self::API_URL, [
                'query' => [
                    'q' => $location,
                    'units' => $units,
                    'appid' => $this->apiKey,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Weather API request failed', [
                'url' => $e->getRequest()->getUri(),
                'message' => $e->getMessage()
            ]);

            throw new WeatherServiceException('Failed to retrieve weather data. Please try again later.', 500);
        }
    }
}
