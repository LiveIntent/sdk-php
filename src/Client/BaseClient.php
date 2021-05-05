<?php

namespace LiveIntent\Client;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Request;
use LiveIntent\Services\TokenService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use LiveIntent\Exceptions\FileNotFoundException;
use LiveIntent\Exceptions\StubNotFoundException;
use LiveIntent\Exceptions\InvalidOptionException;
use Illuminate\Http\Client\Factory as RequestFactory;

class BaseClient extends RequestFactory
{
    /**
     * The default options to use when creating requests.
     *
     * @var array
     */
    private $options = [
        'base_url' => null,
        'client_id' => null,
        'client_secret' => null,
        'tries' => 1,
        'retryDelay' => 100,
        'timeout' => 10,
        'guzzleOptions' => []
    ];

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
     * The token service.
     *
     * @var \LiveIntent\Services\TokenService
     */
    private $tokenService;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge_recursive($this->options, $options);
        $this->recordingsFilepath = $options['recordingsFilepath'] ?? $this->recordingsFilepath;

        $this->stubCallbacks = collect();

        $this->tokenService = new TokenService([
            'client_id' => $this->options['client_id'],
            'client_secret' => $this->options['client_secret'],
        ]);
    }

    /**
     * Issue a request to the api.
     *
     * @param string $method
     * @param string $path
     * @param null|array $data
     * @param array $opts
     * @return \Illuminate\Http\Client\Response
     */
    public function request($method, $path, $data = null, $opts = [])
    {
        return tap($this->newPendingRequest(), function ($request) {
            $this->prepareAuth($request);
        })->send($method, $path, $opts);
    }

    /**
     * Prepare authentication for the request.
     *
     * @param PendingRequest $request
     * @return void
     * @throws Exception
     * @throws RequestException
     */
    public function prepareAuth(PendingRequest $request)
    {
        $options = $request->mergeOptions();

        if (data_has($options, 'headers.Authorizaion') || data_has($options, 'cookies')) {
            return;
        }

        $request->withToken($this->tokenService->token(), $this->tokenService->tokenType());
    }

    /**
     * Create a new pending request instance for this factory.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function newPendingRequest()
    {
        return parent::newPendingRequest()
            ->acceptJson()
            ->timeout($this->options['timeout'])
            ->baseUrl($this->options['base_url'])
            ->withOptions($this->options['guzzleOptions'])
            ->retry($this->options['tries'], $this->options['retryDelay']);
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
        $filepath = $this->getFilepath();

        if (! file_exists($filepath)) {
            throw new FileNotFoundException("Recordings file not found. Path tried: `{$filepath}`");
        }

        $recorded = unserialize(file_get_contents($this->getFilepath()));

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
     * Get the filepath that test data should be stored at.
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->recordingsFilepath;
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

        $filepath = tap($this->getFilepath(), fn ($path) => touch($path));

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
}
