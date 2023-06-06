<?php

namespace LiveIntent\Services;

use LiveIntent\AdSlot;

/**
 * @method \LiveIntent\AdSlot|\LiveIntent\ResourceResponse find($id, $options = null)
 * @method \LiveIntent\AdSlot|\LiveIntent\ResourceResponse create($attributes, $options = null)
 * @method \LiveIntent\AdSlot|\LiveIntent\ResourceResponse update($attributes, $options = null)
 */
class AdSlotService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/ad-slot';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = AdSlot::class;
}
