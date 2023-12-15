<?php

namespace App\Services;

use GuzzleHttp\Client;

class HubSpotService
{
    protected $apiUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->apiUrl = 'https://api.hubapi.com';
        $this->accessToken = config('services.hubspot.access_token');
    }

    public function createContact($data)
    {
        $client = new Client();

    try {
        \Log::info('HubSpot API Request Payload: ' . json_encode(['properties' => $data]));

        $response = $client->post("{$this->apiUrl}/contacts/v1/contact", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => ['properties' => $data],
        ]);

        return json_decode($response->getBody(), true);
    } catch (\Exception $e) {
        \Log::error('HubSpot API Error: ' . $e->getMessage());

        throw $e; // Re-throw the exception to propagate the error
    }
    }
}
