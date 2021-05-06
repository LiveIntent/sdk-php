<?php

namespace Tests\Services;

use LiveIntent\User;
use LiveIntent\Services\TokenService;

class AuthServiceTest extends ServiceTestCase
{
    protected $serviceKey = 'auth';

    public function testGetCurrentUserWithAccessToken()
    {
        $tokenService = new TokenService([
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'base_url' => env('LI_BASE_URL', 'http://localhost:33001'),
        ]);

        $user = $this->service->withToken($tokenService->token())->user();

        $this->assertInstanceOf(User::class, $user);

        // return $this->liAuth->withToken($request->bearerToken())->user();
        // return $this->liAuth->withCookies($cookies, $cookieDomain)->user();
    }
}
