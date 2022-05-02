<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use App\Models\WeatherData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeatherDataTest extends TestCase
{
    public function test_weather_data_is_created_correctly()
    {
        $user = User::factory()->create();
        $city = City::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
    
        $payload = [
            "temperature" => 12,
            "humidity" => 51,
            "description" => "scattered meatballs",
            "city_id" => $city->id
        ];
 
        $this->json('POST', '/api/weather', $payload, $headers)
            ->assertStatus(201)
            ->assertJson([
                "weather_data" => [
                "temperature" => 12,
                "humidity" => 51,
                "description" => "scattered meatballs",
                "city_id" => $city->id
                ]
            ]);
    }

    public function test_weather_data_is_updated_correctly()
    {
        $user = User::factory()->create();
        $wd = WeatherData::factory()->create();
        $city = City::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
    
        $payload = [
            "temperature" => 13,
            "humidity" => 61,
            "description" => "scattered meatballs again",
            "city_id" => $city->id
        ];
 
        $this->json('PUT', '/api/weather/' . $wd->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJson([
                "weather_data" => [
                "temperature" => 13,
                "humidity" => 61,
                "description" => "scattered meatballs again",
                "city_id" => $city->id
                ]
            ]);
    }

    public function tests_weather_data_is_deleted_correctly()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $city = City::factory()->create();
        $headers = ['Authorization' => "Bearer $token"];

        $wd = WeatherData::factory()->create([
            "temperature" => 12,
            "humidity" => 51,
            "description" => "scattered meatballs",
            "city_id" => $city->id
        ]);

        $this->json('DELETE', '/api/weather/' . $wd->id, [], $headers)
            ->assertStatus(204);
    }

    public function test_weather_data_is_listed_correctly()
    {
        $city = City::factory()->create();
        $wd1 = WeatherData::factory()->create([
            "temperature" => 12,
            "humidity" => 51,
            "description" => "scattered meatballs",
            "city_id" => $city->id
        ]);

        $wd2 = WeatherData::factory()->create([
            "temperature" => 13,
            "humidity" => 61,
            "description" => "scattered meatballs again",
            "city_id" => $city->id
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', '/api/weather', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                [
                    "temperature" => 12,
                    "humidity" => 51,
                    "description" => "scattered meatballs",
                    "city_id" => $city->id
                ],
                [
                    "temperature" => 13,
                    "humidity" => 61,
                    "description" => "scattered meatballs again",
                    "city_id" => $city->id
                ]
                
            ])
            ->assertJsonStructure([
                '*' => ['id', 'temperature', 'humidity', 'description', 'city_id', 'deleted_at', 'created_at', 'updated_at'],
            ]);
    }
}
