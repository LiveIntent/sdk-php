<?php

namespace LiveIntent\Services;

use LiveIntent\Resource;
use Illuminate\Http\Client\Response;
use LiveIntent\Client\RequestOptions;
use LiveIntent\Client\ClientInterface;
use LiveIntent\Exceptions\InvalidRequestException;

abstract class AbstractService
{
    /**
     * The client to use for issueing requests.
     */
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
     * Find a resource by its id.
     *
     * @param string|int $id
     * @retrurn \LiveIntent\Resource
     */
    public function find($id)
    {
        return $this->request('get', $this->resourceUrl($id));
    }

    /**
     * Create a new resource.
     *
     * @param array|stdClass|\LiveIntent\Resource $attributes
     * @retrurn \LiveIntent\Resource
     */
    public function create($attributes)
    {
        $data = (array) $attributes;

        if ($attributes instanceof Resource) {
            $data = $attributes->getAttributes();
        }

        return $this->request('post', $this->baseUrl(), $data);
    }

    /**
     * Update an existing resource.
     *
     * @param array|stdClass|\LiveIntent\Resource $attributes
     * @retrurn \LiveIntent\Resource
     */
    public function update($attributes)
    {
        $data = (array) $attributes;
        $id = $data['id'] ?? null;

        if ($attributes instanceof Resource) {
            $id = $attributes->id;
            $data = array_merge($attributes->getDirty(), ['version' => $attributes->version]);
        }

        return $this->request('post', $this->resourceUrl($id), $data);
    }

    // /**
    //  */
    // public function createOrUpdate($attributes, $key = 'id')
    // {
    //     //
    // }

    // /**
    //  */
    // public function createMany($attributeGroups)
    // {
    //     //
    // }

    // /**
    //  */
    // public function updateMany($attributeGroups)
    // {
    //     //
    // }

    // /**
    //  */
    // public function where($field, $operator, $value)
    // {
    //     //
    // }

    // /**
    //  */
    // public function delete($id)
    // {
    //     //
    // }

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
     *
     * @return \LiveIntent\Resource|\Illuminate\Support\Collection
     */
    protected function request(string $method, string $path, array $params = [], ?ApiRequestOptions $opts = null)
    {
        $response = $this->getClient()->request($method, $path, $params, $opts);

        $this->handleErrors($response);

        return $this->morphResponse($response);
    }

    /**
     * Morph the response into more friendly objects. Responses
     * that represent a single entity will return an instance
     * of that entity, while responses that represent more
     * than one entity will be morphed into a collection.
     *
     * @return \LiveIntent\Resource|\Illuminate\Support\Collection
     */
    private function morphResponse(Response $response)
    {
        return $this->newResource($response->json()['output']);
    }

    /**
     * Get the resource's api url, usually it will be
     * in the form of `entity/{id}`.
     *
     * @param string|int $id
     * @return string
     */
    protected function resourceUrl($id)
    {
        return sprintf("%s/$id", $this->baseUrl());
    }

    /**
     * Get the resource's base url. Usually it will just be `/entity`.
     */
    protected function baseUrl()
    {
        return static::BASE_URL;
    }

    /**
     * Create a new resource instance.
     *
     * @param array $body
     * @reutrn \LiveIntent\Resource
     */
    private function newResource($body)
    {
        $class = static::OBJECT_CLASS;
        return new $class($body);
    }

    /**
     * Check for api errors and handle them accordingly.
     *
     * @throws \LiveIntent\AbstractRequestException
     *
     * @return void
     */
    private function handleErrors(Response $response)
    {
        if ($response->successful()) {
            return;
        }

        throw $this->newApiError($response);
    }

    /**
     * Create the proper exception based on an error response.
     *
     * @return \LiveIntent\AbstractRequestException
     */
    private function newApiError(Response $response)
    {
        switch ($response->status()) {
            case 400:
                return InvalidRequestException::factory($response);
            default:
                dump($response->status());
                // return new \Exception();
        }
    }
}
