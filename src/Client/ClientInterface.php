<?php

namespace LiveIntent\Client;

interface ClientInterface
{
    /**
     * TODO
     */
    public function request($method, $path, $data = null, $opts = null);
}
