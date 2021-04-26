<?php

namespace LiveIntent\SDK\Services;

use Illuminate\Http\Client\Factory;

abstract class AbstractService
{
    private $access_token = '';
    private $token_type = '';
    private $expires_in = 0;

    /**
     *
     */
    public function request($method, $path)
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

        return $client->send($method, $path, [
            'headers' => [
                'Authorization' => "Bearer {$this->access_token}"
            ]
        ]);
    }
}
