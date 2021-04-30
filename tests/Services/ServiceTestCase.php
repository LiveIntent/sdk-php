<?php

namespace Tests\Services;

use Tests\TestCase;
use LiveIntent\LiveIntentClient;
use LiveIntent\Services\AbstractService;

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

        // TODO

        $this->service = new $this->serviceClass(
            new LiveIntentClient([
                'client_id' => $_ENV['CLIENT_ID'],
                'client_secret' => $_ENV['CLIENT_SECRET']
            ])
        );
    }
}
