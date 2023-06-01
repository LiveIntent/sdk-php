<?php

namespace LiveIntent;

use Illuminate\Http\Client\Response;

class ResourceResponse
{
    public Resource $resource;
    public Response $rawResponse;

    public function __construct(Resource $resource, Response $rawResponse)
    {
        $this->resource = $resource;
        $this->rawResponse = $rawResponse;
    }
}
