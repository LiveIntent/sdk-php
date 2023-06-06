<?php

namespace LiveIntent\Services;

use LiveIntent\Campaign;

/**
 * @method \LiveIntent\Campaign|\LiveIntent\ResourceResponse find($id, $options = null)
 * @method \LiveIntent\Campaign|\LiveIntent\ResourceResponse create($attributes, $options = null)
 * @method \LiveIntent\Campaign|\LiveIntent\ResourceResponse update($attributes, $options = null)
 */
class CampaignService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/campaign';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = Campaign::class;
}
