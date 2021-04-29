<?php

namespace LiveIntent\Exceptions;

use Illuminate\Http\Client\Response;

abstract class AbstractRequestException extends \Exception
{
    /**
     *
     */
    public static function factory(Response $response)
    {
        return new static($response);
    }
}
