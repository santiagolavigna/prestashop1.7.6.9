<?php

use GuzzleHttp\Client;

class WeatherApi
{
    private $apiKey;
    private $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    public function fetchData($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException('Invalid IP address.');
        }

        $url = "https://api.weatherapi.com/v1/current.json?q={$ip}&key={$this->apiKey}";

        try {
            $response = $this->client->get($url);

            $logMessage = "Request to API: {$url}\nResponse: " . $response->getBody();
            error_log($logMessage);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            $errorMessage = "Error fetching data: " . $e->getMessage();
            error_log($errorMessage);
        }

        return null;
    }
}
