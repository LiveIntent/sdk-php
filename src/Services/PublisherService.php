<?php

namespace LiveIntent\Services;

use LiveIntent\Publisher;

/**
 * @method \LiveIntent\Publisher find($id, $options = null)
 * @method \LiveIntent\Publisher create($attributes, $options = null)
 * @method \LiveIntent\Publisher update($attributes, $options = null)
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
