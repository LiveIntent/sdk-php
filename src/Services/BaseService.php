<?php

namespace LiveIntent\Services;

use Illuminate\Http\Client\Factory;
use LiveIntent\ResourceServiceOptions;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Traits\ForwardsCalls;

class BaseService extends Factory
{
    use ForwardsCalls;
    use Concerns\MocksRequests;
    use Concerns\HandlesApiErrors;
    use Concerns\AuthenticatesRequests;

    /**
     * The default options to use when creating requests.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The currently pending request.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $pendingRequest;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $options)
    {
        parent::__construct();

        $this->options = $options;
    }

    /**
     * Create a new pending request instance for this factory.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function newPendingRequest()
    {
        if (data_get($this->options, 'act_as_user_id') && $this->tokenService) {
            $this->actingAs(data_get($this->options, 'act_as_user_id'));
        }

        $request = tap(new PendingRequest($this), function ($request) {
            $request->buildClient();

            collect(data_get($this->options, 'middleware'))->each(
                fn ($middleware) => $request->withMiddleware($middleware)
            );
        });

        return $request
            ->acceptJson()
            ->baseUrl(data_get($this->options, 'base_url'))
            ->withOptions($this->options)
            ->retry(data_get($this->options, 'tries', 1), data_get($this->options, 'retryDelay', 10));
    }

    /**
     * Impersonate the given user when issuing requests.
     *
     * @param int $userId
     * @return $this
     */
    public function actingAs(int $userId)
    {
        $this->tokenService->actAs($userId);

        return $this;
    }

    /**
     * Send the request to the given URL.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @param ResourceServiceOptions $rsOptions
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function request(
        string $method,
        string $url,
        array $options = [],
        ResourceServiceOptions $rsOptions = null
    ) {
        /** @var PendingRequest $request */
        $request = tap($this->pendingRequest(), function ($request) {
            $this->authenticateRequest($request);
        });

        if ($rsOptions?->manuallyHandleRequestErrors) {
            // Execute as a promise so an exception is not thrown from PendingRequest
            // when the request fails
            // However this will throw exceptions for an invalid request body, or connection issues
            $response = $request->async()
                ->send($method, $url, $options)
                ->then(function ($response) use ($rsOptions) {
                    if ($response instanceof \Throwable) {
                        throw $response;
                    }
                    $this->handleErrors($response, $rsOptions);
                    return $response;
                })->wait();
        }
        else {
            // PendingRequest throws an exception when running as a non-promise
            // This was kept for backwards compatibility as we are not sure
            // what other projects are using this library
            $response = $request->send($method, $url, $options);

            $this->handleErrors($response, $rsOptions);
        }

        return $response;
    }

    /**
     * Attach a json body to the request.
     *
     * @param array $data
     * @return BaseService
     */
    public function withJson(array $data)
    {
        return $this->withBody(json_encode($data), 'application/json');
    }

    /**
     * Get the currently pending request.
     *
     * @return PendingRequest
     */
    public function pendingRequest()
    {
        if (! $this->pendingRequest) {
            $this->pendingRequest = $this->newPendingRequest();
        }

        return $this->pendingRequest;
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param  string  $url
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     */
    public function get(string $url, $query = null)
    {
        return $this->request('GET', $url, [
            'query' => $query,
        ]);
    }

    /**
     * Issue a HEAD request to the given URL.
     *
     * @param  string  $url
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     */
    public function head(string $url, $query = null)
    {
        return $this->request('HEAD', $url, [
            'query' => $query,
        ]);
    }

    /**
     * Issue a POST request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function post(string $url, array $data = [])
    {
        return $this->request('POST', $url, $data);
    }

    /**
     * Issue a PATCH request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function patch($url, $data = [])
    {
        return $this->request('PATCH', $url, $data);
    }

    /**
     * Issue a PUT request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function put($url, $data = [])
    {
        return $this->request('PUT', $url, $data);
    }

    /**
     * Issue a DELETE request to the given URL.
     *
     * @param  string  $arg
     * @return \Illuminate\Http\Client\Response|\LiveIntent\Resource
     */
    public function delete($arg)
    {
        return $this->request('DELETE', $arg);
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
        $result = $this->forwardCallTo($this->pendingRequest(), $method, $parameters);

        if ($result instanceof PendingRequest) {
            return $this;
        }

        return $result;
    }
}
