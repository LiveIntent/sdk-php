<?php

namespace LiveIntent\Services;

use LiveIntent\InsertionOrder;

/**
 * @method \LiveIntent\InsertionOrder|\LiveIntent\ResourceResponse find($id, $options = null)
 * @method \LiveIntent\InsertionOrder|\LiveIntent\ResourceResponse create($attributes, $options = null)
 * @method \LiveIntent\InsertionOrder|\LiveIntent\ResourceResponse update($attributes, $options = null)
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
