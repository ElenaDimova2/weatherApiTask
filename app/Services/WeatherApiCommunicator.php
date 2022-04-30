<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WeatherApiCommunicator{

    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function sendGet(string $url, array $params){
        try{
            $response = $this->client->get($url, $params);
            
            $returnResponse = $this->formatSuccessResponse($response);
        }catch(RequestException $e){
            $returnResponse = $this->formatErrorResponse($e);
        }

        return $returnResponse;
    }

    private function formatSuccessResponse($response){
        $message = $response->getHeader('message');
        $data = json_decode((string) $response->getBody(), true);

        $returnResponse = [
            'status_code' => $response->getStatusCode(),
            'message' => $message,
            'data' => $data
        
        ];

        return $returnResponse;
    }

    private function formatErrorResponse($e){
        return [
            'status_code' => $e->getResponse()->getStatusCode(),
            'message' => $e->getResponse()->getHeader('message'),
            'data' => null
        ];
    }
}
?>