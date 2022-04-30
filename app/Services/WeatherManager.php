<?php

namespace App\Services;

class WeatherManager{
    private $apiCommunicator;

    public function __construct(WeatherApiCommunicator $weatherApiCommunicator){
        $this->apiCommunicator = $weatherApiCommunicator;
    }

    public function getCurrentWeatherData($url)
    {
        $header = [
            'Accept' => 'application/json',
            'X-RapidAPI-Host' => 'community-open-weather-map.p.rapidapi.com',
            'X-RapidAPI-Key' => '638e8e046emsh9381a913cad62f1p1b4039jsnef60c716af41'
        ];

        $data = [
            'headers' => $header
        ];

        return $this->apiCommunicator->sendGet($url, $data);
    }
}
?>