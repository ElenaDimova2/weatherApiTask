<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'id',
        'name',
        'state_code',
        'latitude',
        'longitude'
    ];

    public function weather_data()
    {
        return $this->hasMany(WeatherData::class);
    }

}
