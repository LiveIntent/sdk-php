<?php

namespace LiveIntent;

use LiveIntent\Services\ServiceFactory;

class LiveIntentClient
{
    /**
     * @var \LiveIntent\Services\ServiceFactory
     */
    private $serviceFactory;

    /**
     * The global client options.
     *
     * @var array
     */
    protected $options = [
        'base_url' => null,
        'client_id' => null,
        'client_secret' => null,
        'tries' => 1,
        'retryDelay' => 100,
        'timeout' => 10,
        'guzzleOptions' => []
    ];

    /**
     * Create a new client instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     *
     */
    public function fake()
    {
        $this->options['shouldFake'] = true;

        return $this;
    }

    /**
     *
     */
    public function record()
    {
        $this->options['shouldRecord'] = true;

        return $this;
    }

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
