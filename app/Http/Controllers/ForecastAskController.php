<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForecastService;
use App\Exceptions\ForecastServiceException;

/**
 * Clase ForecastAskController
 *
 * Esta clase es un controlador que proporciona un punto final para obtener el pronóstico del tiempo para una ubicación dada.
 *
 * @package App\Http\Controllers
 */
class ForecastAskController extends Controller
{
    /**
     * El servicio de pronóstico del tiempo.
     *
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
     * Obtiene el pronóstico del tiempo.
     *
     * @param Request $request La solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con el pronóstico del tiempo.
    */
    public function index(Request $request)
    {
        $days = $request->input('days', 1);
        $units = $request->input('units', 'metric');

        try {
            $location = 'Santander, ES';
            $coordinates = $this->forecastService->getCoordinates($location);
            $data = $this->forecastService->getForecast($coordinates['lat'], $coordinates['lon'], $days, $units);

            $forecast = [];
            foreach ($data['daily'] as $day) {
                $forecast[] = [
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
