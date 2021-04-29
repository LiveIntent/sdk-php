<?php

namespace LiveIntent\Exceptions;

use Illuminate\Http\Client\Response;

abstract class AbstractRequestException extends \Exception
{
    /**
     * Create a new exception instance.
     *
     * @return \LiveIntent\Exceptions\AbstractRequestException
     */
    public static function factory(Response $response)
    {
        $code = $response->status();
        $path = $response->effectiveUri();

        return new static("Request to '{$path}' failed with status code [{$code}]. Response: " . $response->body());
    }
}
