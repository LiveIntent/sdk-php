<?php

namespace LiveIntent\Services;

use LiveIntent\Resource;
use LiveIntent\Exceptions;
use LiveIntent\Client\InteractsWithClient;

abstract class AbstractResourceService
{
    use HandlesApiErrors;
    use InteractsWithClient;

    /**
     * The resource's base url. Usually it will just be `/entity`.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The resource class for this entity.
     *
     * @var string
     */
    protected $objectClass;

    /**
     * Find a resource by its id.
     *
     * @param string|int $id
     * @param array $opts
     * @return \LiveIntent\Resource
     */
    public function find($id, $opts = [])
    {
        return $this->request('get', $this->resourceUrl($id), null, $opts);
    }

    /**
     * Create a new resource.
     *
     * @param array|\stdClass|\LiveIntent\Resource $attributes
     * @param array $opts
     * @return \LiveIntent\Resource
     */
    public function create($attributes, $opts = [])
    {
        $payload = (array) $attributes;

        if ($attributes instanceof Resource) {
            $payload = $attributes->getAttributes();
        }

        return $this->request('post', $this->baseUrl, $payload, $opts);
    }

    /**
     * Update an existing resource.
     *
     * @param array|\stdClass|\LiveIntent\Resource $attributes
     * @param array $opts
     * @return \LiveIntent\Resource
     */
    public function update($attributes, $opts = [])
    {
        $payload = (array) $attributes;
        $id = $payload['id'] ?? null;

        if ($attributes instanceof Resource) {
            $id = $attributes->id;
            $payload = array_merge($attributes->getDirty(), ['version' => $attributes->version]);
        }

        if ($id === null) {
            throw Exceptions\InvalidArgumentException::factory($payload, 'Unable to find `id` for update operation');
        }

        return $this->request('post', $this->resourceUrl($id), $payload, $opts);
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
     * Make a request to the api.
     *
     * @param null|array $params
     * @param array $opts
     * @return \LiveIntent\Resource
     */
    protected function request(string $method, string $path, $params = null, $opts = [])
    {
        $response = $this->getClient()->request($method, $path, $params, $opts);

        $this->handleErrors($response);

        // TODO - handle multiple, handle other structures
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
        return sprintf("%s/$id", $this->baseUrl);
    }

    /**
     * Create a new resource instance.
     *
     * @param array $body
     * @return \LiveIntent\Resource
     */
    private function newResource($body)
    {
        $class = $this->objectClass;

        return new $class($body);
    }
}
