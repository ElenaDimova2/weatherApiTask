<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "temperature" => $this->faker->randomNumber(),
            "humidity" => $this->faker->randomNumber(),
            "description" => $this->faker->text(),
            "city_id" => City::factory()
        ];
    }
}
