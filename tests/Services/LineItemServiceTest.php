<?php

namespace Tests\Services;

use LiveIntent\LineItem;
use LiveIntent\Services\LineItemService;
use LiveIntent\Exceptions\InvalidRequestException;

class LineItemServiceTest extends ServiceTestCase
{
    public const TEST_RESOURCE_ID = 192431;
    public const TEST_RESOURCE_HASH_ID = '00009758365a11e7943622000a974651';

    protected $serviceClass = LineItemService::class;

    public function testIsFindable()
    {
        $lineItem = $this->service->find(self::TEST_RESOURCE_ID);
        $this->assertInstanceOf(LineItem::class, $lineItem);

        $lineItem = $this->service->find(self::TEST_RESOURCE_HASH_ID);
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testIsCreatable()
    {
        $lineItem = $this->service->create([
            'name' => 'SDK Test',
            'status' => 'paused',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => 'fef81b06365911e7943622000a974651',
        ]);

        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testWhatHappensWhenThereIsAnError()
    {
        $this->expectException(InvalidRequestException::class);

        $this->service->create([
            'name' => 'SDK Test',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => 'fef81b06365911e7943622000a974651',
        ]);
    }
}
