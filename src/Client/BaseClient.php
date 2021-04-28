<?php

namespace LiveIntent\Client;

use Illuminate\Http\Client\Factory;
use LiveIntent\Client\ClientInterface;

class BaseClient implements ClientInterface
{
    private $access_token;

    /**
     *
     */
    public function request($method, $path, $data = null, $opts = null)
    {
        $factory = new Factory();
        $client = $factory->withOptions([
            'base_uri' => 'http://localhost:33001',
        ]);

        $client_id = 'ari';
        $client_secret =  '93f129a60f17264feab81a260256f13e';

        if (!$this->access_token) {
            $response = $client->asForm()->post('oauth/token', [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'client_credentials',
                'scope' => 'openid'
            ]);
            $this->access_token = $response->json()['access_token'];

            dump($this->access_token);

        }

        $factory = new Factory();
        $client = $factory->withOptions([
            'base_uri' => 'http://localhost:33001',
        ]);

        $response = $client->send($method, $path, [
            'headers' => [

                'Authorization' => "Bearer {$this->access_token}",
                'Content-Type' => 'application/json'
            ],
            'json' => (array) $data
        ]);

        return $response;
    }
}
