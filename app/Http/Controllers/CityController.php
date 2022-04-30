<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Models\City;
use App\Models\WeatherData;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $cities = City::all();
            return response()->json(['cities' => $cities], 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        try{
            $city = new City();
            $city->name = $request->name;
            $city->state_code = $request->state_code;
            $city->latitude = $request->latitude;
            $city->longitude = $request->longitude;
            $city->save();
            return response()->json('Successfull stored data', 201);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $city = City::find($id);
            return response()->json($city, 200);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $city = City::findOrFail($id);
            $city->delete();
            return response()->json('', 204);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    public function getCitiesWeather(City $city, $cityName)
    {
        $city = City::where('name', $cityName)->exists();
        if(!$city){
            return response()->json("No cities were found with the name " . $cityName);
        }

        $city = City::where('name', $cityName)
        ->first();
        $wd = WeatherData::where('city_id', $city->id)->get();

        $cityInfo = [
            'city' => $city,
            'weatherData' => $wd
        ];
        
        return response()->json($cityInfo, 200);
    }
}
