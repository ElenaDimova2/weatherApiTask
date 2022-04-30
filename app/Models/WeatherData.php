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

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
