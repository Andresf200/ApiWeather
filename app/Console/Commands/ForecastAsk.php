<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ForecastService;
use App\Exceptions\ForecastServiceException;

/**
 * Clase ForecastAsk
 *
 * Esta clase es un comando de consola que permite obtener el pronóstico del tiempo para santander con preguntas.
 *
 * @package App\Console\Commands
 */
class ForecastAsk extends Command
{
    /**
     * La firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'forecast:ask';

    /**
     * La descripción del comando de consola.
     *
     * @var string
     */
    protected $description = 'Ask for forecast information';

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
        $days = $this->ask('How many days to forecast?');
        $units = $this->choice('What unit of measure?', ['metric', 'imperial'], 0);

        try {
            $location = 'Santander, ES';
            $coordinates = $this->forecastService->getCoordinates($location);
            $data = $this->forecastService->getForecast($coordinates['lat'], $coordinates['lon'], $days, $units);

            foreach ($data['daily'] as $day) {
                $this->info(date('M d, Y', $day['dt']));
                $this->info('Weather: ' . $day['weather'][0]['description']);
                $this->info('Temperature: ' . $day['temp']['day'] . " °" . ($units == 'imperial' ? 'F' : 'C'));
            }
        } catch (ForecastServiceException $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
