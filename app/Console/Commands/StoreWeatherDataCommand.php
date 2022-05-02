<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\WeatherData;
use App\Services\WeatherManager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class StoreWeatherDataCommand extends Command
{
    private $weatherManager;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs on every hour and stores weather data for each city';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(WeatherManager $weatherManager)
    {
        parent::__construct();
        $this->weatherManager = $weatherManager;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cities = City::all();
        
        $weatherManager = $this->weatherManager;
        $bar = $this->output->createProgressBar($cities->count());
        
        if($cities->count() == 0){
            $this->line("No cities in DB");
            return 0;
        }

        foreach ($cities as $city){
            
            $key = config('services.openweathermap.key');
            $lat = $city->latitude;
            $lon = $city->longitude;
            $params = "lat=$lat&lon=$lon&appid=$key&units=metric";
            $url = config('services.openweathermap.daily_weather_url') . "?$params";
            
            $wd = $weatherManager->getCurrentWeatherData($url);

            if($wd['status_code'] != 200){
                $this->line("Problem occured when retrieving data from weather api for city: " . $city->name);
                continue;
            }

            $responseData = $wd['data'];

            $weatherData = [
                'temperature' => $responseData['main']['temp'],
                'humidity' => $responseData['main']['humidity'],
                'description' => $responseData['weather'][0]['description'],
                'city_id' => $city->id
            ];

            $newWD = new WeatherData();
            $newWD->temperature = $weatherData['temperature'];
            $newWD->humidity = $weatherData['humidity'];
            $newWD->description = $weatherData['description'];
            $newWD->city_id = $weatherData['city_id'];

            if(!$newWD->save()){
                $this->line("Problem occured when saving weather data data for city: " . $city->name);
                continue;
            }
            $bar->advance();
        }
        
        $bar->finish();

        $this->line("Weather Data for cities was consumed");

        return 1;
    }
}
