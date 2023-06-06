<?php

namespace LiveIntent\Services;

use LiveIntent\Publisher;

/**
 * @method \LiveIntent\Publisher|\LiveIntent\ResourceResponse find($id, $options = null)
 * @method \LiveIntent\Publisher|\LiveIntent\ResourceResponse create($attributes, $options = null)
 * @method \LiveIntent\Publisher|\LiveIntent\ResourceResponse update($attributes, $options = null)
 */
class PublisherService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/publisher';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = Publisher::class;
}
