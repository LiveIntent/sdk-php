<?php

namespace LiveIntent\Services;

use Illuminate\Http\Client\Response;
use LiveIntent\Client\RequestOptions;
use LiveIntent\Client\ClientInterface;
use LiveIntent\Exceptions\InvalidRequestException;

abstract class AbstractService
{
    private ClientInterface $client;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     */
    public function find($id)
    {
        //
    }

    /**
     */
    public function create($attributes)
    {
        //
    }

    /**
     */
    public function update($attributes)
    {
        //
    }

    /**
     */
    public function createOrUpdate($attributes, $key = 'id')
    {
        //
    }

    /**
     */
    public function createMany($attributeGroups)
    {
        //
    }

    /**
     */
    public function updateMany($attributeGroups)
    {
        //
    }

    /**
     */
    public function where($field, $operator, $value)
    {
        //
    }

    /**
     */
    public function delete($id)
    {
        //
    }

    /**
     * Get the client used by the service to make requests.
     *
     * @return \LiveIntent\SDK\ClientInterface
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

        return new $cls($response->json()['output']);
    }

    /**
     *
     */
    private function handleErrors(Response $response)
    {
        if ($response->successful()) {
            return;
        }

        throw $this->checkApiError($response);
    }

    /**
     *
     */
    private function checkApiError(Response $response)
    {
        switch ($response->status()) {
            case 400:
                return InvalidRequestException::factory($response);
        }
    }
}
