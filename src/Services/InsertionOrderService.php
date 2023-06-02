<?php

namespace LiveIntent\Services;

use LiveIntent\InsertionOrder;

/**
 * @method \LiveIntent\InsertionOrder find($id, $options = null)
 * @method \LiveIntent\InsertionOrder create($attributes, $options = null)
 * @method \LiveIntent\InsertionOrder update($attributes, $options = null)
 */
class InsertionOrderService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/insertion-order';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = InsertionOrder::class;
}
