<?php

namespace LiveIntent\SDK;

use LiveIntent\SDK\Services\LineItemService;

class LiveIntentClient
{
    public $lineItems;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($attrs)
    {
        $this->lineItems = new LineItemService();
    }
}
