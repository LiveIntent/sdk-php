<?php

namespace LiveIntent\Services;

use Illuminate\Support\Collection;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Traits\ForwardsCalls;
use LiveIntent\Exceptions\FileNotFoundException;
use LiveIntent\Exceptions\StubNotFoundException;
use LiveIntent\Exceptions\InvalidOptionException;

abstract class AbstractService extends Factory
{
    use ForwardsCalls;
    use HandlesApiErrors;
    use AuthenticatesRequests;

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
     * Whether the request/response pairs should be stored for later use.
     *
     * @var bool
     */
    private $shouldSaveRecordings = false;

    /**
     * The filepath to use for reading and storing responses.
     *
     * @var string
     */
    private $recordingsFilepath = 'tests/__snapshots__/snapshot';

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
        $request = new PendingRequest();

        return $request
            ->acceptJson()
            ->timeout($this->options['timeout'])
            ->baseUrl($this->options['base_url'])
            ->withOptions($this->options['guzzleOptions'])
            ->retry($this->options['tries'], $this->options['retryDelay']);
    }

    /**
     * Send the request to the given URL.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return \Illuminate\Http\Client\Response
     */
    public function request(string $method, string $url, array $options = [])
    {
        $request = tap($this->pendingRequest(), function ($request) {
            $this->authenticateRequest($request);
        });

        $response = $request->send($method, $url, $options);

        $this->handleErrors($response);

        return $response;
    }

    /**
     * Attach a json body to the request.
     *
     * @param array $data
     * @return PendingRequest
     */
    public function withJson(array $data)
    {
        return $this->withBody(json_encode($data), 'application/json');
    }

    /**
     * Instruct the client to use fake responses.
     *
     * @param  callable|array  $callback
     * @return $this
     */
    public function fake($callback = null)
    {
        if ($callback !== null) {
            return parent::fake($callback);
        }

        return parent::fake(function (Request $request) {
            if ($this->shouldSaveRecordings) {
                throw new InvalidOptionException('Cannot use the `fake` option together with the `saveRecordings` option.');
            }

            $response = $this->findMockedResponse($request);

            if (! $response) {
                throw StubNotFoundException::factory($request);
            }

            return $this->response($response['body'], $response['status'], $response['headers']);
        });
    }

    /**
     * Save request/response pairs for later mocking.
     *
     * @return $this
     */
    public function saveRecordings()
    {
        $this->record();

        $this->shouldSaveRecordings = true;

        return $this;
    }

    /**
     * Find a response stub that matches the request.
     *
     * @return null|\Illuminate\Http\Client\Response
     */
    public function findMockedResponse(Request $request)
    {
        if (! file_exists($this->recordingsFilepath)) {
            throw new FileNotFoundException("Recordings file not found. Path tried: `{$filepath}`");
        }

        $recorded = unserialize(file_get_contents($this->recordingsFilepath));

        $match = collect($recorded)->first(fn ($pair) => $this->isSameRequest($pair[0], $request));

        return $match[1] ?? null;
    }

    /**
     * Record a request response pair.
     *
     * @param  \Illuminate\Http\Client\Request  $request
     * @param  \Illuminate\Http\Client\Response  $response
     * @return void
     */
    public function recordRequestResponsePair($request, $response)
    {
        parent::recordRequestResponsePair($request, $response);

        if ($this->shouldSaveRecordings) {
            $recorded = collect($this->recorded)->map(function ($pair) {
                return [$pair[0], [
                    'body' => $pair[1]->body(),
                    'headers' => $pair[1]->headers(),
                    'status' => $pair[1]->status(),
                ]];
            });

            $this->saveRequestResponsePairs($recorded);
        }
    }

    /**
     * Save recorded request response pairs to storage.
     *
     * @param array $recordings
     * @return void
     */
    protected function saveRequestResponsePairs(Collection $recording)
    {
        if ($this->stubCallbacks->isNotEmpty()) {
            throw new InvalidOptionException('Cannot use the `fake` option together with the `saveRecordings` option.');
        }

        $filepath = tap($this->recordingsFilepath, fn ($path) => touch($path));

        $previouslyRecorded = unserialize(file_get_contents($filepath)) ?: collect();

        $snapshots = collect($previouslyRecorded)
            ->concat($recording)
            ->keyBy(fn ($item) => $this->getRequestChecksum($item[0]));

        file_put_contents($filepath, serialize($snapshots));
    }

    /**
     * Determine if two requests should be considered the same.
     *
     * @return bool
     */
    private function isSameRequest(Request $a, Request $b)
    {
        return $this->getRequestChecksum($a) === $this->getRequestChecksum($b);
    }

    /**
     * Get a checksum of a request so we can compare if requests are the same.
     *
     * @return string
     */
    private function getRequestChecksum(Request $request)
    {
        // We need to some normalizing of the request data since the
        // incoming request and saved request look a bit different
        $data = $request->isJson()
              ? json_decode(collect($request->data())->flip()->first(), true)
              : $request->data();

        // ignore these keys when preforming the comparison
        $excludedKeys = ['version', 'client_id', 'client_secret'];

        $parts = [
            $request->method(),
            $request->url(),
            collect($data)->except($excludedKeys)->toArray(),
        ];

        return hash('crc32b', collect($parts)->map('json_encode')->join(''));
    }

    /**
     * Get the currently pending request.
     *
     * @return PendingRequest
     */
    public function pendingRequest()
    {
        if (!$this->pendingRequest) {
            $this->pendingRequest = $this->newPendingRequest();
        }

        return $this->pendingRequest;
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
