# Usage

The LiveIntent PHP SDK provides a convenient way to interact with the LiveIntent API in PHP applications.

## Installation

```
composer require liveintent/sdk-php
```

## Getting Started

Simple usage looks like this:

```php
$liveIntent = new \LiveIntent\LiveIntentClient([
    'base_url' => 'http://localhost:6207',
    'personal_access_token' => '<your-personal-access-token>',
]);

$lineItem = $liveIntent->lineItems->find(123);

echo $lineItem->id;
```

### Services

Currently available services can be found by looking [here](/src/Services/ServiceFactory.php#L27).

### Override example (todo)

```php
$response = $liveIntent->request()->get('/auth/user/4090');
$response->status();
```

## Request Options

When creating a client, you pay pass an array of options to further configure the client. If you require a per-request configuration, the individual service methods will also accept this optional array.

#### Global configuration example
```php
$liveIntent = new \LiveIntent\LiveIntentClient([
    'base_url' => 'qa-heimdall.liveintenteng.com', // base url of the api
    'personal_access_token' => '<your-pat>',       // your personal access token
    'client_id' => '<your-client-id>',             // your client id (if not using pat)
    'client_secret' => '<your-client-secret>',     // your client secret (if not using pat)
    'tries' => 3,                                  // number of retries per request
    'timeout' => 10,                               // number of seconds to wait on a response before hangup
    'retryDelay' => 10,                            // number of seconds to wait between retries
    'recordingsFilepath' => '/tmp/snapshotfile',   // filepath where test snapshots should be saved (see Testing)
    'guzzleOptions' => [],                         // additional guzzle options see (https://docs.guzzlephp.org/en/stable/request-options.html)
]);
```

#### Per-request override example
```php
$liveIntent->lineItems
    ->retry(3, 10)
    ->baseUrl('localhost:1234')
    ->withOptions([])
    ->find(123);
]);
```

## Logging

Will be handled soon.
