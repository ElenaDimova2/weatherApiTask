<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->city(),
            "state_code" => strtoupper(Str::random(3)),
            "latitude" => $this->faker->latitude(),
            "longitude" => $this->faker->longitude()
        ];
    }
}
