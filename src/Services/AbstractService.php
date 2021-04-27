<?php

namespace LiveIntent\SDK\Services;

use Illuminate\Http\Client\Factory;
use LiveIntent\SDK\Exceptions\ResourceNotFoundException;

abstract class AbstractService
{
    private $access_token = '';
    private $token_type = '';
    private $expires_in = 0;

    /**
     * Find an api resource by its primary key.
     *
     * @param  int|string  $id
     * @return null|\LiveIntent\SDK\ApiResources\AbstractApiResource
     */
    abstract public function find($id);

    /**
     * Find an api resource by its primary key.
     *
     * @param  int|string  $id
     * @return \LiveIntent\SDK\ApiResources\AbstractApiResource
     *
     * @throws \LiveIntent\SDK\Exceptions\ResourceNotFoundException
     */
    abstract public function findOrFail($id);


    /**
     *
     */
    protected function request($method, $path, $resource)
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

        $response = $client->send($method, $path, [
            'headers' => [
                'Authorization' => "Bearer {$this->access_token}"
            ]
        ]);

        if ($response->ok()) {
            return new $resource($response->json()['output']);
        }

        return null;
    }

    /**
     *
     */
    protected function requestOrFail($method, $path, $resource)
    {
        $resource = $this->request($method, $path, $resource);

        if (!$resource) {
            throw new ResourceNotFoundException();
        }

        return $resource;
    }

}
