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
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $client = $this->createClient();

        $this->service = new $this->serviceClass($client);
    }

    /**
     * Create the service client to use for the tests.
     *
     * @return \LiveIntent\Client\ClientInterface
     */
    private function createClient()
    {
        $client = new LiveIntentClient([
            'client_id' => $_ENV['CLIENT_ID'],
            'client_secret' => $_ENV['CLIENT_SECRET'],
            'base_url' => $_ENV['LI_BASE_URL'],
        ]);

        if (env('RECORD_SNAPSHOTS')) {
            $client->saveRecordings();
        } elseif (env('USE_SNAPSHOTS', true)) {
            $client->fake();
        }

        return $client;
    }
}
