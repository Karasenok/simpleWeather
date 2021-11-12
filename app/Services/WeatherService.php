<?php

namespace App\Services;

use App\Http\Resources\WeatherResource;
use Fresh\Transliteration\Transliterator;
use GuzzleHttp\Client;
use Stevebauman\Location\Facades\Location;


class WeatherService
{
    const NOT_FOUND_ERROR = 'Город не найден.';
    const INTERNAL_SERVER_ERROR = 'Произошла внутренняя ошибка сервера.';
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

    public function getUserLocation($request):string{

        $currentUserInfo = Location::get($request->ip());
        return $currentUserInfo->cityName?:'Москва';
    }

    protected function getRequestParams($city) :string{
        return "q={$city}&lang=ru&units=metric&appid=".env('WEATHER_KAY');//todo add custom params
    }

    protected function endpointRequest($params) :\stdClass{
        try {

            $response = $this->client->get( $params);

        } catch (\Exception $e) {

          abort(response()->json(
              [
                  'data' => ['error' => self::NOT_FOUND_ERROR,'status' => 404] //todo add exceptions.
              ]
          ));
        }

        return $this->responseHandler($response->getBody()->getContents());
    }

    protected function responseHandler($response) :\stdClass {
        if ($response) {
            $dirtyData = json_decode($response);
        }else{
            abort(response()->json(
                [
                    'data' => [
                        'error' => self::INTERNAL_SERVER_ERROR,
                        'status' => 500
                    ]
                ]
            ));
        }

       return $dirtyData;
    }

}
