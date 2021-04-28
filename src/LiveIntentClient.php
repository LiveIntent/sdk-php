<?php

namespace LiveIntent;

use LiveIntent\Services;
use LiveIntent\Client\BaseClient;

class LiveIntentClient extends BaseClient
{
    protected static $classMap = [
        'lineItems' => Services\LineItemService::class
    ];

    protected $services = [];

    /**
     *
     */
    public function __get($name)
    {
        if (!\array_key_exists($name, static::$classMap)) {
            return null;
        }

        if (!\array_key_exists($name, $this->services)) {
            $this->services[$name] = new static::$classMap[$name]($this);
        }

        return $this->services[$name];
    }
}
