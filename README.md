# LiveIntent SDK PHP

[![Test](https://github.com/LiveIntent/sdk-php/actions/workflows/run-tests.yml/badge.svg)](https://github.com/LiveIntent/sdk-php/actions/workflows/run-tests.yml)
[![Latest Stable Version](https://poser.pugx.org/liveintent/sdk-php/v/stable.svg)](https://packagist.org/packages/liveintent/sdk-php)
[![License](https://poser.pugx.org/liveintent/sdk-php/license)](//packagist.org/packages/liveintent/sdk-php)

The LiveIntent PHP SDK provides a convenient way to interact with the LiveIntent API in PHP applications.

This document describes how to develop the SDK itself. For usage documentation and examples see [usage](/docs/usage.md).

## Getting started

First clone the repository. Then, run `cp .env.example .env` to create the env file.

Finally install the dependencies via:

```bash
composer install
```

## Testing

To run the unit tests

```php
composer test
```

### Mocking

When testing you often want to mock external api calls, but you also want to be confident that those api calls will work in the real world.

To solve this, you may instruct the client to record the request/response pairs it makes. This allows you to run your tests against a live version of the api when necessary, and to reuse those same responses when mocking is acceptable for faster, more predictable and reliable tests.

#### Test in record mode

To run the tests against a live api connection and record the results run:

```php
composer test-record
```

By default recordings will be saved in the file `tests/__snapshots__/snapshot`.

#### Test using recorded responses

To run the tests against a live api connection and record the results run:

```php
composer test
```

#### Alternative methods of mocking

The LiveIntent client inherits from Laravel's Http Client. Therefore, all the methods available to that client are also be available.

For detailed documentation see [here](https://laravel.com/docs/8.x/http-client#testing).

## Linting

The installed linter will auto-format your code to comply with our agreed php coding standard.

To run the linter
```php
composer lint
```


