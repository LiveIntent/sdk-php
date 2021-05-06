<?php

namespace LiveIntent\Client;

class BaseClient
{
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

    }

    /**
     *
     */
    public function record()
    {

    }
}
