<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ForecastService;
use App\Exceptions\ForecastServiceException;

/**
 * Clase ForecastCommand
 *
 * Esta clase es un comando de consola que permite obtener el pronóstico del tiempo para una ubicación dada.
 *
 * @package App\Console\Commands
*/
class ForecastCommand extends Command
{
    /**
     * La firma del comando de consola.
     *
     * @var string
    */
    protected $signature = 'forecast {location=Santander,ES} {--d=1} {--u=metric}';

    /**
     * La descripción del comando de consola.
     *
     * @var string
    */
    protected $description = 'Get the weather forecast for max 5 days for the given location.';

    /**
     * El servicio de pronóstico del tiempo.
     *
     * @var ForecastService
    */
    private $forecastService;

    /**
     * Crea una nueva instancia del comando.
     *
     * @param ForecastService $forecastService El servicio de pronóstico del tiempo.
    */
    public function __construct(ForecastService $forecastService)
    {
        parent::__construct();
        $this->forecastService = $forecastService;
    }

    /**
     * Ejecuta el comando de consola.
     *
     * Obtiene el pronóstico del tiempo para la ubicación dada y lo muestra en la consola.
     * Si ocurre un error al obtener el pronóstico del tiempo, muestra un mensaje de error en la consola.
    */
    public function handle()
    {
        $location = $this->argument('location');
        $days = $this->option('d');
        $units = $this->option('u');

        try{
        $coordinates = $this->forecastService->getCoordinates($location);
        $data = $this->forecastService->getForecast($coordinates['lat'], $coordinates['lon'], $days, $units);

        $this->info("{$location}:");
        foreach ($data['daily'] as $day) {
            $this->info(date('M d, Y', $day['dt']));
            $this->info("> Weather: {$day['weather'][0]['description']}");
            $this->info("> Temperature: {$day['temp']['day']} °" . ($units == 'imperial' ? 'F' : 'C'));
        }
        } catch (ForecastServiceException $e) {
            $this->error($e->getMessage());
        }
    }
}
