<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class CityInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city:info {--cityName=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve information for a single city by its name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cityName = $this->option('cityName');
        $out = new ConsoleOutput();
        $cityInfo = City::where('name', $cityName)
        ->leftJoin('weather_data', 'cities.id', 'weather_data.city_id')
        ->get()
        ->map(function($city){
            return [
                'id' => $city->city_id,
                'name' => $city->name,
                'state_code' => $city->state_code,
                'latitude' => $city->latitude,
                'longitute' => $city->longitude,
                'temperature' => $city->temperature,
                'humidity' => $city->humidity,
                'description' => $city->description,
                'date' => $city->created_at
            ];
        });

        if($cityInfo->count() == 0){
            $out->writeln(PHP_EOL . "No cities were found with the name " . $cityName);
            return 0;
        }
        $this->table(['id', 'name', 'state_code', 'latitude', 'longitude', 'temperature', 'humidity', 'weather_description'], $cityInfo->toArray());

        return 1;
    }
}
