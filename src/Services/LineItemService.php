<?php

namespace LiveIntent\SDK\Services;

use LiveIntent\SDK\ApiResources\LineItem;

// there is a reason we do this, it's for DX, so we can type hint
// and comment all the methods. the file will be auto generated anyway

class LineItemService extends AbstractService
{
    /**
     * Find an api resource by its primary key.
     *
     * @param  int|string  $id
     * @return null|\LiveIntent\SDK\ApiResources\LineItem
     */
    public function find($id)
    {
        return $this->request('get', "strategy/{$id}", LineItem::class);
    }

    /**
     *
     */
    public function create($attributes)
    {
        return $this->request('post', "strategy", LineItem::class, $attributes);
    }
}
