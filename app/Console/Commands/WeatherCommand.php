<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Services\WeatherService;
use App\Exceptions\WeatherServiceException;

/**
 * Clase WeatherCommand
 *
 * Esta clase es un comando de consola que permite obtener los datos actuales del clima para una ubicación dada.
 *
 * @package App\Console\Commands
*/
class WeatherCommand extends Command
{
    /**
     * La firma del comando de consola.
     *
     * @var string
    */
    protected $signature = 'current {location=Santander,ES} {--u=metric}';
    /**
     * La descripción del comando de consola.
     *
     * @var string
    */
    protected $description = 'Get the current weather data for the given location.';
    /**
     * El servicio de clima.
     *
     * @var WeatherService
    */
    private $weatherService;

    /**
     * Crea una nueva instancia del comando.
     *
     * @param WeatherService $weatherService El servicio de clima.
    */
    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    /**
     * Ejecuta el comando de consola.
     *
     * Obtiene los datos del clima para la ubicación dada y los muestra en la consola.
     * Si ocurre un error al obtener los datos del clima, muestra un mensaje de error en la consola.
    */
    public function handle()
    {
        $location = $this->argument('location');
        $units = $this->option('u');

        try {
            $data = $this->weatherService->getWeather($location, $units);
            $this->displayWeather($data, $units);
        } catch (WeatherServiceException $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Muestra los datos del clima en la consola.
     *
     * @param array $data Los datos del clima.
     * @param string $units Las unidades de medida para la temperatura.
    */
    private function displayWeather(array $data, string $units): void
    {
        $this->info("{$data['name']} ({$data['sys']['country']})");
        $this->info("> Weather: {$data['weather'][0]['description']}");
        $this->info("> Temperature: {$data['main']['temp']} °" . ($units == 'imperial' ? 'F' : 'C'));
    }
}
