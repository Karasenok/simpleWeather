<?php

namespace App\Http\Controllers;


use App\Http\Resources\WeatherResource;
use App\Services\WeatherService;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;
    public function __construct(WeatherService $weatherService){
        $this->weatherService = $weatherService;
    }

    public function index(){

        return view('weather');
    }

    public function show($city = '') :WeatherResource {

        $city =  $city ?:$this->weatherService->getUserLocation(\request());

        return new WeatherResource($this->weatherService->getByCity($city));
    }


}
