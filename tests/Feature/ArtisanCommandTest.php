<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArtisanCommandTest extends TestCase
{
    public function test_weather_data_consume_command()
    {
        $city = City::factory()->create([
            "name" => "London",
            "state_code" => "JS",
            "latitude" => 51.509865,
            "longitude" => -0.118092
        ]);

        $this->artisan("weather:consume")
            ->expectsOutput("Weather Data for cities was consumed")
            ->assertExitCode(1);

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('get', '/api/weather/1', [], $headers)
        ->assertStatus(200)
        ->assertJson([
            "city_id" => $city->id
        ]);
    }

    public function test_weather_data_consume_no_cities_found()
    {
        $this->artisan("weather:consume")
        ->expectsOutput("No cities in DB")
        ->assertExitCode(0);
    }

    public function test_city_information_command()
    {
        $city = City::factory()->create([
            "name" => "London",
            "state_code" => "JS",
            "latitude" => 51.509865,
            "longitude" => -0.118092
        ]);

        $this->artisan("city:info --cityName=London")
            ->expectsTable([
                'id', 
                'name', 
                'state_code', 
                'latitude', 
                'longitude', 
                'temperature', 
                'humidity', 
                'weather_description', 
                'date'],[
                    [$city->id, "London", "JS", 51.509865, -0.118092]
                ])
            ->assertExitCode(0);
    }

    public function test_city_information_command_no_cities_found()
    {
        $this->artisan("city:info --cityName=London")
            ->expectsOutput("No cities were found with the name London")
            ->assertExitCode(0);
    }

    public function test_city_information_command_multiple_cities_found()
    {
        $city1 = City::factory()->create([
            "name" => "London",
            "state_code" => "JS",
            "latitude" => 51.509865,
            "longitude" => -0.118092
        ]);

        $city2 = City::factory()->create([
            "name" => "London",
            "state_code" => "MKD",
            "latitude" => 55.509865,
            "longitude" => -10.118092
        ]);

        $this->artisan("city:info --cityName=London")
            ->expectsOutput("Multiple cities were found with the name London")
            ->expectsQuestion("What is the state code of the city you are looking for?", "JS")
            ->expectsTable([
                'id', 
                'name', 
                'state_code', 
                'latitude', 
                'longitude', 
                'temperature', 
                'humidity', 
                'weather_description', 
                'date'],[
                    [$city1->id, "London", "JS", 51.509865, -0.118092]
                ])
            ->assertExitCode(0)
            ->assertExitCode(0);
    }
}
