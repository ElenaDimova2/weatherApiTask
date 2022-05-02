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
     * @return array
     */
    public function handle()
    {
        $cityName = $this->option('cityName');

        $city = City::where('name', $cityName)->get();
        $cityCount = $city->count();

        if($cityCount == 0){
            $this->line("No cities were found with the name " . $cityName);
            return 0;
        }
        if($cityCount > 1){
            $this->line("Multiple cities were found with the name " . $cityName);
            $stateCode = City::where('name', $cityName)->pluck('state_code')->toArray();
            $state_code = $this->choice('What is the state code of the city you are looking for?', $stateCode, $stateCode[0], 2, false);
        }

        $cityInfo = City::where('name', $cityName);

        if(isset($state_code)){
            $cityInfo = $cityInfo->where('state_code', $state_code);
        }

        $cityInfo = $cityInfo->leftJoin('weather_data', 'cities.id', 'weather_data.city_id')
        ->select(
            'cities.id',
            'cities.name',
            'cities.state_code',
            'cities.latitude',
            'cities.longitude',
            'weather_data.temperature',
            'weather_data.humidity',
            'weather_data.description',
            'weather_data.created_at',
        )
        ->get();
        
        $this->table(['id', 'name', 'state_code', 'latitude', 'longitude', 'temperature', 'humidity', 'weather_description', 'date'], $cityInfo->toArray());
        
        return 0;
    }
}
