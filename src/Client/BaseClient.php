<?php

namespace LiveIntent\Client;

use LiveIntent\Services\TokenService;
use Illuminate\Http\Client\Factory as IlluminateClient;

class BaseClient extends IlluminateClient implements ClientInterface
{
    /**
     * The base url for all api requests issued by this client.
     *
     * @var string
     */
    private $baseUrl = 'http://localhost:33001'; // TODO change

    /**
     * The default number of times a request should be retried.
     *
     * This may be overridden on a per request basis.
     *
     * @var int
     */
    private $tries = 1;

    /**
     * The default number of milliseconds to delay before retrying.
     *
     * This may be overridden on a per request basis.
     *
     * @var int
     */
    private $retryDelay = 100;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->baseUrl = $options['base_url'] ?? $this->baseUrl;

        $this->tokenService = new TokenService([
            'client_id' => $options['client_id'] ?? null,
            'client_secret' => $options['client_secret'] ?? null,
            'base_url' => $this->baseUrl,
        ]);
    }

    /**
     * Issue a request to the api.
     *
     * @param string $method
     * @param string $path
     * @param null|array $data
     * @param null|array $opts
     * @return \Illuminate\Http\Client\Response
     */
    public function request($method, $path, $data = null, $opts = [])
    {
        return $this
            ->newPendingRequest()
            ->baseUrl($this->baseUrl)
            ->withToken($this->tokenService->token(), $this->tokenService->tokenType())
            ->withBody($data, 'application/json')
            ->asJson()
            ->acceptJson()
            ->retry($opts['tries'] ?? $this->tries, $opts['retryDelay'] ?? $this->retryDelay)
            ->send($method, $path, $opts);
    }
}
