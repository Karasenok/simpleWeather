<?php

namespace App\Services;

use App\Http\Resources\WeatherResource;
use Fresh\Transliteration\Transliterator;
use GuzzleHttp\Client;
use Stevebauman\Location\Facades\Location;

class WeatherService
{
    protected $client;
    protected $transliterator;

    public function __construct(Client $client){
        $this->client = $client;
        $this->transliterator =  new Transliterator();
    }

    public function getByCity($city) :\stdClass {
        $city = $this->transliterator->ruToEn($city);

        return $this->endpointRequest("weather?".$this->getRequestParams($city));
    }

    public function getUserLocation():string{
        $ip = '2.92.219.151'; /* My IP address */
        $currentUserInfo = Location::get($ip); //change to $request->ip()
        return $currentUserInfo->cityName?:'Москва';
    }

    public function getRequestParams($city) :string{
        return "q={$city}&lang=ru&units=metric&appid=".env('WEATHER_KAY');
    }

    public function endpointRequest($params) :\stdClass{
        //$response = $this->client->get( $params);

        try {

            $response = $this->client->get( $params);

        } catch (\Exception $e) {

          abort(response()->json(
              [
                  'data' => ['error' => 'Город не найден.']
              ]
          ));
        }

        return $this->responseHandler($response->getBody()->getContents());
    }

    public function responseHandler($response) :\stdClass {
        if ($response) {
            $dirtyData = json_decode($response);

        }else{
           // abort(500);
        }
       return $dirtyData;
    }

}
