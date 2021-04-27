<?php

namespace LiveIntent\SDK\Services;

use LiveIntent\SDK\ApiResources\LineItem;

// there is a reason we do this, it's for DX, so we can type hint
// and comment all the methods. the file will be auto generated anyway

class LineItemService extends AbstractService
{
    /**
     *
     */
    public function find($id): LineItem
    {
        return $this->get('strategy', $id, LineItem::class);
    }

    /**
     *
     */
    public function findOrFail($id): LineItem
    {
        return $this->getOrFail('strategy', $id, LineItem::class);
    }
}
