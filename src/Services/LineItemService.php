<?php

namespace LiveIntent\Services;

use LiveIntent\LineItem;

// there is a reason we do this, it's for DX, so we can type hint
// and comment all the methods. the file will be auto generated anyway

/**
 * @method \LiveIntent\LineItem find($id)
 * @method \LiveIntent\LineItem create($attributes)
 * @method \LiveIntent\LineItem update($attributes)
 */
class LineItemService extends AbstractService
{
    public const BASE_URL = '/strategy';
    public const OBJECT_CLASS = LineItem::class;
}
