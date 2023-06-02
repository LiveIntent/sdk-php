<?php

namespace LiveIntent\Services;

use LiveIntent\LineItem;

/**
 * @method \LiveIntent\LineItem find($id, $options = null)
 * @method \LiveIntent\LineItem create($attributes, $options = null)
 * @method \LiveIntent\LineItem update($attributes, $options = null)
 */
class LineItemService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/strategy';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = LineItem::class;
}
