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
     * The default number of seconds to wait on a request before giving up.
     *
     * This may be overridden on a per request basis.
     *
     * @var int
     */
    private $timeout = 10;

    /**
     * Extra optional guzzle override options.
     *
     * @var array
     */
    private $guzzleOptions = [];

    /**
     * The token service.
     *
     * @var \LiveIntent\Services\TokenService
     */
    private $tokenService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->tries = $options['tries'] ?? $this->tries;
        $this->timeout = $options['timeout'] ?? $this->timeout;
        $this->baseUrl = $options['base_url'] ?? $this->baseUrl;
        $this->retryDelay = $options['retryDelay'] ?? $this->retryDelay;
        $this->guzzleOptions = $options['guzzleOptions'] ?? $this->guzzleOptions;

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
     * @param array $opts
     * @return \Illuminate\Http\Client\Response
     */
    public function request($method, $path, $data = null, $opts = [])
    {
        return $this
            ->newPendingRequest()
            ->baseUrl($this->baseUrl)
            ->withToken($this->tokenService->token(), $this->tokenService->tokenType())
            ->withBody(json_encode($data), 'application/json')
            ->acceptJson()
            ->timeout($this->timeout)
            ->retry($opts['tries'] ?? $this->tries, $opts['retryDelay'] ?? $this->retryDelay)
            ->withOptions($this->guzzleOptions)
            ->send($method, $path, $opts);
    }
}
