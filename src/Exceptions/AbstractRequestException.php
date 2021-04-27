<?php

namespace LiveIntent\SDK\Exceptions;

abstract class AbstractRequestException extends \Exception
{
    /**
     *
     */
    public static function factory($response)
    {
        return new static;
    }
}
