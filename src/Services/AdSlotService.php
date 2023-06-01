<?php

namespace LiveIntent\Services;

use LiveIntent\AdSlot;

/**
 * @method \LiveIntent\AdSlot find($id, $options = null)
 * @method \LiveIntent\AdSlot create($attributes, $options = null)
 * @method \LiveIntent\AdSlot update($attributes, $options = null)
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
