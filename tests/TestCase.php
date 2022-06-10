<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        // Do something which should run for each test file (if needed), env variable loading is moved to bootstraping
    }
}
