<?php

namespace LiveIntent\Services;

use LiveIntent\Campaign;

/**
 * @method \LiveIntent\Campaign find($id, $options = null)
 * @method \LiveIntent\Campaign create($attributes, $options = null)
 * @method \LiveIntent\Campaign update($attributes, $options = null)
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
