<?php

namespace lukawar\ClickmeetingApi\Clients;

use lukawar\ClickmeetingApi\Contracts\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ClickMeetingApiClient implements HttpClientInterface
{
    protected Client $client;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->client = new Client([
            'base_uri' => $apiUrl,
            'headers' => [
                'X-Api-Key' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function get(string $endpoint): array
    {
        return $this->request('GET', $endpoint);
    }

    public function post(string $endpoint, array $data): array
    {
        return $this->request('POST', $endpoint, $data);
    }

    private function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = $method === 'POST' ? ['json' => $data] : [];
            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}