<?php

namespace LiveIntent;

use Illuminate\Http\Client\Response;

class ResourceResponse
{
    public Resource|null $resource;
    public Response $response;

    public function __construct(Resource|null $resource, Response $rawResponse)
    {
        $this->resource = $resource;
        $this->response = $rawResponse;
    }
}
