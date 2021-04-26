<?php

namespace LiveIntent\SDK\Services;

class LineItemService extends AbstractService
{
    /**
     *
     */
    public function find($id)
    {
        return $this->request('GET', "strategy/{$id}");
    }
}
