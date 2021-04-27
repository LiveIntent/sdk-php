<?php

namespace LiveIntent\SDK\Services;

use LiveIntent\SDK\Exceptions\InvalidRequestException;
use LiveIntent\SDK\Util\ApiRequestOptions;
use LiveIntent\SDK\LiveIntentClientInterface;

abstract class AbstractService
{
    private LiveIntentClientInterface $client;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(LiveIntentClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Find an api resource by its primary key.
     *
     * @param  int|string  $id
     * @return null|\LiveIntent\SDK\ApiResources\AbstractApiResource
     *
     * @throws \LiveIntent\SDK\Exceptions\ResourceNotFoundException
     */
    abstract public function find($id);

    /**
     * Create a new resource via the api.
     *
     * @param  array  $attributes
     * @return \LiveIntent\SDK\ApiResources\AbstractApiResource
     */
    abstract public function create($attributes);

    /**
     * Get the client used by the service to make requests.
     *
     * @return \LiveIntent\SDK\LiveIntentClientInterface
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * Make a request to the api.
     */
    protected function request(string $method, string $path, string $cls, array $params = [], ?ApiRequestOptions $opts = null)
    {
        $response = $this->getClient()->request($method, $path, $params, $opts);
        $this->handleErrors($response);

        if ($response->failed()) {
            dump($response->json());
            // do something
        }

        // $response->throw();

        return new $cls($response->json()['output']);
    }

    /**
     *
     */
    private function handleErrors($response)
    {
        $error = $this->findApiError($response);

        if ($error) {
            throw $error;
        }
    }

    /**
     *
     */
    private function findApiError($response)
    {
        if ($response->successful()) {
            return;
        }

        switch ($response->status()) {
            case 400:
                return InvalidRequestException::factory($response);
        }
    }
}
