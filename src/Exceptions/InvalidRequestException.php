<?php

namespace LiveIntent\Exceptions;

class InvalidRequestException extends AbstractRequestException
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($response)
    {
        parent::__construct($response->body());
    }
}
