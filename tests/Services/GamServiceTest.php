<?php

namespace Tests\Services;

use Tests\Fixtures;
use LiveIntent\Exceptions\InvalidRequestException;

class GamServiceTest extends ServiceTestCase
{
    protected $serviceKey = 'gam';

    public function testCreateGamUrl()
    {
        $adSlotId = Fixtures::adSlotId();
        $googleAccountId = 123456;
        $gamUrl = $this->service->getGamUrl($adSlotId, $googleAccountId);
        $this->assertNotNull($gamUrl);
    }

    public function testThrowsWhenInvalidAccountIdIsPassed()
    {
        $this->expectException(InvalidRequestException::class);
        $adSlotId = Fixtures::adSlotId();
        $googleAccountId = null;
        $this->service->getGamUrl($adSlotId, $googleAccountId);
    }

    public function testThrowsWhenInvalidAdSlotIdIsPassed()
    {
        $this->expectException(InvalidRequestException::class);
        $adSlotId = -1;
        $googleAccountId = 123456;
        $this->service->getGamUrl($adSlotId, $googleAccountId);
    }
}
