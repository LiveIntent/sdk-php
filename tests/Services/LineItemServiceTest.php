<?php

namespace Tests\Services;

use LiveIntent\LineItem;
use LiveIntent\Services\LineItemService;
use LiveIntent\Exceptions\InvalidRequestException;
use LiveIntent\Exceptions\InvalidArgumentException;

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

        $lineItem = new LineItem([
            'name' => 'SDK Test',
            'status' => 'paused',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => 'fef81b06365911e7943622000a974651',
        ]);
        $lineItem = $this->service->create($lineItem);
        $this->assertInstanceOf(LineItem::class, $lineItem);
        $this->assertNotNull($lineItem->id);
    }

    public function testIsUpdateable()
    {
        $lineItem = $this->service->create([
            'name' => 'SDK Test',
            'status' => 'paused',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => 'fef81b06365911e7943622000a974651',
        ]);

        $this->assertInstanceOf(LineItem::class, $lineItem);

        $lineItem = $this->service->update([
            'id' => $lineItem->id,
            'version' => $lineItem->version,
            'name' => 'SDK Test Updated',
        ]);

        $this->assertInstanceOf(LineItem::class, $lineItem);
        $this->assertEquals('SDK Test Updated', $lineItem->name);

        $lineItem->name = 'Updated again';
        $lineItem = $this->service->update($lineItem);

        $this->assertInstanceOf(LineItem::class, $lineItem);
        $this->assertEquals('Updated again', $lineItem->name);

        $this->expectException(InvalidArgumentException::class);

        $lineItem = $this->service->update([
            'version' => $lineItem->version,
            'name' => 'should break',
        ]);
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
