<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\WeatherRequest;
use App\Exceptions\WeatherServiceException;

/**
* Clase WeatherController
*
* Esta clase es un controlador que maneja las solicitudes HTTP relacionadas con el clima.
*
* @package App\Http\Controllers
*/
class WeatherController extends Controller
{
     /**
     * El servicio de clima.
     *
     * @var WeatherService
     */
    private $weatherService;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @param WeatherService $weatherService El servicio de clima.
     */
    public function __construct(WeatherService $weatherService){
        $this->weatherService = $weatherService;
    }

    /**
     * Maneja la solicitud GET para obtener los datos del clima.
     *
     * @param WeatherRequest $request La solicitud HTTP.
     * @return JsonResponse Los datos del clima o un mensaje de error.
     */
    public function index(WeatherRequest $request){
        try {
            $location = $request->input('location');
            $units = $request->input('units');

            $data = $this->weatherService->getWeather($location, $units);

            return $this->createSuccessResponse($data, $units);
        } catch (WeatherServiceException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Crea una respuesta de éxito con los datos del clima.
     *
     * @param array $data Los datos del clima.
     * @param string $units Las unidades de medida para la temperatura.
     * @return JsonResponse La respuesta de éxito con los datos del clima.
    */
    private function createSuccessResponse(array $data, string $units): JsonResponse
    {
        return response()->json([
            'location' => "{$data['name']} ({$data['sys']['country']})",
            'weather' => $data['weather'][0]['description'],
            'temperature' => "{$data['main']['temp']} °" . ($units == 'imperial' ? 'F' : 'C'),
        ]);
    }
}
