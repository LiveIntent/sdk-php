<?php

namespace LiveIntent\SDK\Services;

use LiveIntent\SDK\ApiResources\LineItem;

class LineItemService extends AbstractService
{
    /**
     *
     */
    public function find($id): LineItem
    {
        $response = $this->request('GET', "strategy/{$id}");

        if ($response->ok()) {
            return new LineItem($response->json()['output']);
        }
    }
}
