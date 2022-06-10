<?php

namespace Tests;

use Dotenv\Dotenv;
use LiveIntent\Services\Concerns\MocksRequests;

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

class TestBootStrap
{
    use MocksRequests;

    public static function init()
    {
        return new TestBootStrap();
    }

    private function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__FILE__) . '/../');
        $dotenv->safeLoad();

        // clear snapshots if tests are running in recording mode
        if (env('RECORD_SNAPSHOTS', false)) {
            $this->clearSnapShots();
        }
    }
}

// initialize TestBootstrap class
TestBootStrap::init();
