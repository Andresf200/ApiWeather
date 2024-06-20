<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForecastService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ForecastRequest;
use App\Exceptions\ForecastServiceException;

/**
* Clase ForecastController
*
* Esta clase es un controlador que maneja las solicitudes HTTP relacionadas con el clima para suministrar  pronostico en dias.
*
* @package App\Http\Controllers
*/
class ForecastController extends Controller
{
    /**
     *
     * El servicio de pronóstico del tiempo.
     * @var ForecastService
    */
    private $forecastService;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @param ForecastService $forecastService El servicio de pronóstico del tiempo.
     */
    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    /**
     * Maneja la solicitud GET para obtener los datos del pronóstico del tiempo.
     *
     * @param ForecastRequest $request La solicitud HTTP.
     * @return JsonResponse Los datos del pronóstico del tiempo o un mensaje de error.
     */
    public function index(ForecastRequest $request)
    {
        $location = $request->input('location');
        $days = $request->input('days', 1);
        $units = $request->input('units', 'metric');

        try{
            $coordinates = $this->forecastService->getCoordinates($location);
            $data = $this->forecastService->getForecast($coordinates['lat'], $coordinates['lon'], $days, $units);

            $forecast = [
                'location' => $location,
                'forecast' => []
            ];

            // Iterar sobre cada día en los datos del pronóstico
            foreach ($data['daily'] as $day) {
                // Añadir la información del día al array de pronóstico
                $forecast['forecast'][] = [
                    'date' => date('M d, Y', $day['dt']),
                    'weather' => $day['weather'][0]['description'],
                    'temperature' => $day['temp']['day'] . " °" . ($units == 'imperial' ? 'F' : 'C')
                ];
            }

            return response()->json($forecast);
        } catch (ForecastServiceException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
