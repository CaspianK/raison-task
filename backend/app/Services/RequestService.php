<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RequestService
{

    /**
     * @throws GuzzleException
     */
    public function request(
        string $method,
        string $url,
        array $data = [],
        array $headers = []
    ): array {
        $client = new Client(
            [
                'timeout' => 5,
            ]
        );

        $headers = array_merge($headers, [
            'Content-Type' => 'application/json',
        ]);

        $response = $client->request($method, $url, [
            'headers' => $headers,
            'json' => $data
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
