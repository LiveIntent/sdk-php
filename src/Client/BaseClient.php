<?php

namespace LiveIntent\Client;

use LiveIntent\Client\ClientInterface;
use Illuminate\Http\Client\Factory as IlluminateClient;

class BaseClient extends IlluminateClient implements ClientInterface
{
    private $access_token;

    private $clientId;
    private $clientSecret;

    private $baseUrl = 'http://localhost:33001'; // TODO change

    private $tries = 1;

    private $retryDelay = 100; // ms

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->clientId = $options['client_id'] ?? null;
        $this->clientSecret = $options['client_secret'] ?? null;
        $this->baseUrl = $options['base_url'] ?? $this->baseUrl;

        parent::__construct();
    }

    private function usesClientCredentials()
    {
        return $this->clientId && $this->clientSecret;
    }

    /**
     *
     */
    public function obtainAccessToken()
    {
        $factory = new IlluminateClient();
        $client = $factory->withOptions([
            'base_uri' => $this->baseUrl
        ]);

        $response = $client->asForm()->post('oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'openid'
        ]);

        return $response->json()['access_token'];
    }

    /**
     *
     */
    public function request($method, $path, $data = null, $opts = [])
    {
        if (!$this->access_token) { // needs token
            $this->access_token = $this->obtainAccessToken();
        }

        return $this
            ->newPendingRequest()
            ->baseUrl($this->baseUrl)
            ->withToken($this->access_token)
            ->withBody($data, 'application/json')
            ->asJson()
            ->acceptJson()
            ->retry($this->tries, $this->retryDelay)
            ->send($method, $path, $opts);
    }
}
