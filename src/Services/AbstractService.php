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
     * @param mixed $attributes
     * @retrurn \LiveIntent\Resource
     */
    public function create($attributes)
    {
        $data = (array) $attributes;

        if ($attributes instanceof Resource) {
            $data = $attributes->getAttributes();
        }

        return $this->request('post', $this->classUrl(), $data);
    }

    /**
     * Update an existing resource.
     *
     * @param mixed $attributes
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
    protected function request(string $method, string $path, array $params = [], ?ApiRequestOptions $opts = null)
    {
        $response = $this->getClient()->request($method, $path, $params, $opts);

        $this->handleErrors($response);

        return $this->newResource($response->json()['output']);
    }

    /**
     *
     */
    public function resourceUrl($id)
    {
        return sprintf("%s/$id", static::API_URL);
    }

    /**
     *
     */
    public function classUrl()
    {
        return static::API_URL;
    }

    /**
     *
     */
    private function newResource($body)
    {
        $cls = static::OBJECT_TYPE;
        return new $cls($body);
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
            default:
                dump($response->status());
                return new \Exception();
        }
    }
}
