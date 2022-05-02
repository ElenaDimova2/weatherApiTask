<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeatherDataRequest;
use App\Models\WeatherData;
use Illuminate\Http\Request;

class WeatherDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $weatherData = WeatherData::all();
            return response()->json($weatherData, 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreWeatherDataRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWeatherDataRequest $request)
    {
        try{
            $weatherData = new WeatherData();
            $weatherData->temperature = $request->temperature;
            $weatherData->humidity = $request->humidity;
            $weatherData->description = $request->description;
            $weatherData->city_id = $request->city_id;
            $weatherData->save();
            return response()->json(["messager"=>'Successfull stored data', "weather_data" => $weatherData], 201);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WeatherData $weatherData
     * @return \Illuminate\Http\Response
     */
    public function show($id, WeatherData $weatherData)
    {
        try{
            return response()->json($weatherData::find($id), 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\StoreWeatherDataRequest  $request
     * @param  \App\Models\WeatherData $weatherData
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWeatherDataRequest $request, WeatherData $weatherData, $id)
    {
        try{
            $weatherData = WeatherData::findOrFail($id);
            $weatherData->temperature = $request->temperature;
            $weatherData->humidity = $request->humidity;
            $weatherData->description = $request->description;
            $weatherData->city_id = $request->city_id;
            $weatherData->save();
            return response()->json(["message"=>'Successfull updated data', "weather_data" => $weatherData], 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WeatherData  $weatherData
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, WeatherData $weatherData)
    {
        try{
            $weatherData::findOrFail($id)->delete();
            return response()->json('', 204);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }
}
