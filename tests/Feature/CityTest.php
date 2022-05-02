<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use App\Models\WeatherData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CityTest extends TestCase
{
   public function test_cities_are_created_correctly()
   {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
    
        $payload = [
            "name" => "Skopje",
            "state_code" => "MKD",
            "latitude" => 12,
            "longitude" => -13
        ];

        $this->json('POST', '/api/city', $payload, $headers)
            ->assertStatus(201)
            ->assertJson([
                "city" => [
                    'id' => 1, 
                    "name" => "Skopje",
                    "state_code" => "MKD",
                    "latitude" => 12,
                    "longitude" => -13
                ]
            ]);
   }

   public function test_cities_are_updated_correctly()
   {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $city = City::factory()->create([
            "name" => "Skopje",
            "state_code" => "MKD",
            "latitude" => 12,
            "longitude" => -13
        ]);

        $payload = [
            "name" => "Veles",
            "state_code" => "MKD",
            "latitude" => 13,
            "longitude" => -12
        ];

        $this->json('PUT', '/api/city/' . $city->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJson([
                "city" => [
                    'id' => 1, 
                    "name" => "Veles",
                    "state_code" => "MKD",
                    "latitude" => 13,
                    "longitude" => -12
                ]
            ]);
   }

    public function tests_cities_are_deleted_correctly()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $city = City::factory()->create([
            "name" => "Skopje",
            "state_code" => "MKD",
            "latitude" => 12,
            "longitude" => -13
        ]);

        $this->json('DELETE', '/api/city/' . $city->id, [], $headers)
            ->assertStatus(204);
    }

    public function test_cities_are_listed_correctly()
    {
        $city = City::factory()->create([
            "name" => "Skopje",
            "state_code" => "MKD",
            "latitude" => 12,
            "longitude" => -13
        ]);

        $city = City::factory()->create([
            "name" => "Veles",
            "state_code" => "MKD",
            "latitude" => 15,
            "longitude" => -14
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', '/api/city', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                [
                    "name" => "Skopje",
                    "state_code" => "MKD",
                    "latitude" => 12,
                    "longitude" => -13
                ],
                [
                    "name" => "Veles",
                    "state_code" => "MKD",
                    "latitude" => 15,
                    "longitude" => -14
                ]
                
            ])
            ->assertJsonStructure([
                '*' => ['id', 'name', 'state_code', 'latitude', 'longitude', 'deleted_at', 'created_at', 'updated_at'],
            ]);
    }

    public function test_cities_weather_data_is_shown_correctly()
    {
        $city = City::factory()->create([
            "name" => "Skopje",
            "state_code" => "MKD",
            "latitude" => 12.0,
            "longitude" => -13.0,
            "updated_at" => Carbon::now()->timestamp,
            "created_at" => Carbon::now()->timestamp
        ]);

        $wd = WeatherData::factory()->create([
            "temperature" => 12.0,
            "humidity" => 51.0,
            "description" => "scattered meatballs",
            "city_id" => $city->id,
            "updated_at" => Carbon::now()->timestamp,
            "created_at" => Carbon::now()->timestamp
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', "/api/city/$city->name/weather", [], $headers)
        ->assertStatus(200)
        ->assertJson([
                "id" => $city->id,
                "name" => "Skopje",
                "state_code" => "MKD",
                "latitude" => '12.0',
                "longitude" => '-13.0',
                "deleted_at" => null,
                "created_at" => $city->created_at->jsonSerialize(),
                "updated_at" => $city->updated_at->jsonSerialize(),
                "weather_data" => [[
                    "id" => $wd->id,
                    "temperature" => '12.0',
                    "humidity" => '51.0',
                    "description" => "scattered meatballs",
                    "city_id" => $city->id,
                    "deleted_at" => null,
                    "created_at" => $wd->created_at->jsonSerialize(),
                    "updated_at" => $wd->updated_at->jsonSerialize(),
                ]]
            ]);
    }

    public function test_cities_weather_data_when_invalid_city_is_passed()
    {

        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', "/api/city/skopje/weather", [], $headers)
        ->assertStatus(204);
    }
}
