<?php

namespace LiveIntent;

use LiveIntent\Client\BaseClient;
use LiveIntent\Services\ServiceFactory;

class LiveIntentClient extends BaseClient
{
    /**
     * @var \LiveIntent\Services\ServiceFactory
     */
    private $serviceFactory;

    /**
     * Dynamically resolve a service instance. This makes it easy
     * to access individual services directly as getters on the
     * client rather than instantiating every single service.
     *
     * @param string $name
     * @return null|\LiveIntent\Services\AbstractResourceService
     */
    public function __get($name)
    {
        if (null === $this->serviceFactory) {
            $this->serviceFactory = new ServiceFactory($this->options);
        }

        return $this->serviceFactory->make($name);
    }
}
