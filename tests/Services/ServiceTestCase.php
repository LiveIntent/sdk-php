<?php

namespace Tests\Services;

use Tests\TestCase;
use LiveIntent\SDK\LiveIntentClient;
use LiveIntent\SDK\Services\AbstractService;

class ServiceTestCase extends TestCase
{
    /**
     * The service class under test.
     *
     * @var string
     */
    protected $serviceClass = null;

    /**
     * The service under test.
     */
    protected AbstractService $service;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new $this->serviceClass(
            new LiveIntentClient([
                'client_id' => 'ari',
                'client_secret' => '93f129a60f17264feab81a260256f13e'
            ])
        );
    }
}
