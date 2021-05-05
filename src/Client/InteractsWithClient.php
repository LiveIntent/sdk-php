<?php

namespace LiveIntent\Client;

use LiveIntent\Client\BaseClient;

trait InteractsWithClient
{
    /**
     * The client to use for issueing requests.
     */
    private BaseClient $client;

    /**
     * Get the client used by the service to make requests.
     *
     * @return \LiveIntent\Client\BaseClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the client to use.
     *
     * @param \LiveIntent\Client\BaseClient $client
     * @return void
     */
    public function setClient(BaseClient $client)
    {
        $this->client = $client;
    }
}
