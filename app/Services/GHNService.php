<?php

namespace App\Services;

use GuzzleHttp\Client;

class GHNService
{
	protected $client;
    protected $baseUrl = 'https://dev-online-gateway.ghn.vn/shiip/public-api';
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->token = env('GHN_API_TOKEN');
    }

    public function fetchProvinces()
    {
        $response = $this->client->get("{$this->baseUrl}/master-data/province", [
            'headers' => [
                'Token' => $this->token,
            ],
        ]);
        return json_decode($response->getBody(), true)['data'] ?? [];
    }

    public function fetchDistricts($provinceId)
    {
        $response = $this->client->post("{$this->baseUrl}/master-data/district", [
            'headers' => [
                'Token' => $this->token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'province_id' => $provinceId,
            ],
        ]);
        return json_decode($response->getBody(), true)['data'] ?? [];
    }

    public function fetchWards($districtId)
    {
        $response = $this->client->post("{$this->baseUrl}/master-data/ward", [
            'headers' => [
                'Token' => $this->token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'district_id' => $districtId,
            ],
        ]);
        return json_decode($response->getBody(), true)['data'] ?? [];
    }
}