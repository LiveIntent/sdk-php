<?php

namespace LiveIntent\Services;

use LiveIntent\Advertiser;

/**
 * @method \LiveIntent\Advertiser|\LiveIntent\ResourceResponse find($id, $options = null)
 * @method \LiveIntent\Advertiser|\LiveIntent\ResourceResponse create($attributes, $options = null)
 * @method \LiveIntent\Advertiser|\LiveIntent\ResourceResponse update($attributes, $options = null)
 */
class AdvertiserService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/advertiser';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = Advertiser::class;
}
