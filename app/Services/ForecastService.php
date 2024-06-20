<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ForecastServiceException;

/**
 * Clase ForecastService
 *
 * Esta clase es un servicio que proporciona métodos para obtener datos del clima de la API de OpenWeatherMap.
 *
 * @package App\Services
 */
class ForecastService
{
    /**
     * La URL de la API de OpenWeatherMap.
     *
     * @var string
    */
    private const API_URL = 'https://api.openweathermap.org/data/3.0/onecall';
    private const GEO_API_URL = 'http://api.openweathermap.org/geo/1.0/direct';

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
     * Obtiene el pronóstico del tiempo para una ubicación específica.
     *
     * @param float $lat La latitud de la ubicación.
     * @param float $lon La longitud de la ubicación.
     * @param int $days El número de días para obtener el pronóstico.
     * @param string $units Las unidades a usar para el pronóstico.
     * @return array Los datos del pronóstico.
     * @throws ForecastServiceException Si hay un error obteniendo los datos del pronóstico.
    */
    public function getForecast(float $lat, float $lon, int $days, string $units)
    {
        try{

            $response = $this->client->get(self::API_URL, [
                'query' => [
                    'lat' => $lat,
                    'lon' => $lon,
                    'exclude' => 'minutely,hourly',
                    'units' => $units,
                    'appid' => $this->apiKey,
                ]
            ]);
            $data = json_decode($response->getBody(), true);

            // Limitar los datos a un número específico de días
            $data['daily'] = array_slice($data['daily'], 0, $days);
            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Error getting forecast data', [
                'url' => $e->getRequest()->getUri(),
                'message' => $e->getMessage()
            ]);

            throw new ForecastServiceException('Error getting forecast data: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene las coordenadas para una ubicación específica.
     *
     * @param string $location El nombre de la ubicación.
     * @return array Los datos de las coordenadas.
     * @throws ForecastServiceException Si hay un error obteniendo las coordenadas.
    */
    public function getCoordinates(string $location)
    {
        try{
            $response = $this->client->get(self::GEO_API_URL, [
                'query' => [
                    'q' => $location,
                    'limit' => 1,
                    'appid' => $this->apiKey,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data[0];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Error getting coordinates', [
                'url' => $e->getRequest()->getUri(),
                'message' => $e->getMessage()
            ]);

            throw new ForecastServiceException('Error getting coordinates: ' . $e->getMessage());
        }
    }
}
