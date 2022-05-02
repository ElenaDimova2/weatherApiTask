<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeatherData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'temperature',
        'humidity',
        'description',
        'city_id'
    ];

    protected $units = [
        'kelvin',
        'celsius',
        'fahrenheit'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getTemperatureInUnit(string $metricUnit = 'celsius')
    {
        $metricUnit = strtolower($metricUnit);

        if(!in_array($metricUnit, $this->units)){
            return "Unit $metricUnit is not supported";
        }
        
        if($metricUnit == 'kelvin'){
            return $this->getTemperatureAttribute($this->temperature) + 273.15;
        }

        if($metricUnit == 'fahrenheit'){
            return $this->getTemperatureAttribute($this->temperature) * 1.8 + 32;
        }

        return $this->getTemperatureAttribute($this->temperature);
    }
}
