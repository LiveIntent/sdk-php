<?php

namespace LiveIntent\Client;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Traits\ForwardsCalls;
use LiveIntent\Exceptions\StubNotFoundException;
use LiveIntent\Exceptions\InvalidOptionException;

class RequestBuilder
{
    use ForwardsCalls;

    private $request;

    // /**
    //  * Issue a request to the api.
    //  *
    //  * @param string $method
    //  * @param string $path
    //  * @param null|array $data
    //  * @param array $opts
    //  * @return \Illuminate\Http\Client\Response
    //  */
    // public function request($method, $path, $data = null, $opts = [])
    // {
    //     return tap($this->newPendingRequest(), function ($request) {
    //         $this->prepareAuth($request);
    //     })->send($method, $path, $opts);
    // }

    /**
     * Prepare authentication for the request.
     *
     * @param PendingRequest $request
     * @return void
     * @throws Exception
     * @throws RequestException
     */
    public function prepareAuth()
    {
        $options = $this->mergeOptions();

        if (data_get($options, 'headers.Authorizaion') || data_get($options, 'cookies')) {
            return;
        }

        $this->withToken($this->tokenService->token(), $this->tokenService->tokenType());
    }

    /**
     * Execute a method against the current pending request instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!$this->request) {
            $this->request = $this->newPendingRequest();
        }

        $this->forwardCallTo($this->request, $method, $parameters);
    }
}
