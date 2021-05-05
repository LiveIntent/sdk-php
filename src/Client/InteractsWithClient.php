<?php

namespace LiveIntent\Client;

use LiveIntent\Client\ClientInterface;

trait InteractsWithClient
{
    /**
     * The client to use for issueing requests.
     */
    private ClientInterface $client;

    /**
     * Get the client used by the service to make requests.
     *
     * @return \LiveIntent\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the client to use.
     *
     * @param ClientInterface $client
     * @return void
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }
}
