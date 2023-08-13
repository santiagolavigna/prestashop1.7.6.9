<?php
//use PHPUnit\Framework\TestCase;
//require_once 'classes/WeatherApi.php';
//
//class WeatherApiTest extends TestCase
//{//Mock data
//    private $api_key = '29a33aed8e12469e86a100903230408';
//    private $ip = '5.203.211.175';
//    private $invalid_ip = '9999.9999.9999.9999';
//
//    public function testFetchData()
//    {
//        $apiKey = $this->api_key;
//        $weatherApi = new WeatherApi($apiKey);
//
//        $ip = $this->ip;
//        $data = $weatherApi->fetchData($ip);
//
//        $this->assertNotNull($data);
//        $this->assertArrayHasKey('current', $data);
//        $this->assertArrayHasKey('condition', $data['current']);
//    }
//
//    public function testFetchDataInvalidIP()
//    {
//        $this->expectException(InvalidArgumentException::class);
//
//        $apiKey = $this->api_key;
//        $weatherApi = new WeatherApi($apiKey);
//
//        $invalidIp = $this->invalid_ip;
//        $weatherApi->fetchData($invalidIp);
//    }
//}
