<?php

namespace Tests\Services;

use LiveIntent\User;
use LiveIntent\Services\AuthService;

class AuthServiceTest extends ServiceTestCase
{
    protected $serviceClass = AuthService::class;

    public function testGetCurrentUserWithAccessToken()
    {
        $user = $this->service->withToken($token)->user();

        $this->assertInstanceOf(User::class, $user);

        // return $this->liAuth->withToken($request->bearerToken())->user();
        // return $this->liAuth->withCookies($cookies, $cookieDomain)->user();
    }
}
