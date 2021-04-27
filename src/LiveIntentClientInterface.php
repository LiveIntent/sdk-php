<?php

namespace LiveIntent\SDK;

interface LiveIntentClientInterface
{
    /**
     * TODO
     */
    public function request($method, $path, $data = null, $opts = null);
}
