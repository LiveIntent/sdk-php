<?php

namespace Tests\Services;

use Tests\TestCase;
use LiveIntent\LiveIntentClient;
use LiveIntent\Services\AbstractResourceService;

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
    protected AbstractResourceService $service;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $client = $this->createClient();

        $this->service = new $this->serviceClass();
        $this->service->setClient($client);
    }

    /**
     * Create the service client to use for the tests.
     *
     * @return \LiveIntent\Client\ClientInterface
     */
    private function createClient()
    {
        $client = new LiveIntentClient([
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'base_url' => env('LI_BASE_URL', 'http://localhost:33001'),
        ]);

        if (env('RECORD_SNAPSHOTS')) {
            $client->saveRecordings();
        } elseif (env('USE_SNAPSHOTS', true)) {
            $client->fake();
        }

        return $client;
    }
}
