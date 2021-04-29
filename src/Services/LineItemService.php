<?php

namespace LiveIntent\Services;

use LiveIntent\LineItem;

// there is a reason we do this, it's for DX, so we can type hint
// and comment all the methods. the file will be auto generated anyway

/**
 * @method \LiveIntent\LineItem find($id, $opts = [])
 * @method \LiveIntent\LineItem create($attributes, $opts = [])
 * @method \LiveIntent\LineItem update($attributes, $opts = [])
 */
class LineItemService extends AbstractService
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
