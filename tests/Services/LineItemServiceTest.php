<?php

namespace Tests\Services;

use Tests\Fixtures;
use LiveIntent\LineItem;
use LiveIntent\Services\LineItemService;
use LiveIntent\Exceptions\InvalidRequestException;

class LineItemServiceTest extends ServiceTestCase
{
    protected $serviceClass = LineItemService::class;

    public function testIsFindable()
    {
        $lineItem = $this->service->find(Fixtures::lineItemId());
        $this->assertInstanceOf(LineItem::class, $lineItem);

        $lineItem = $this->service->find(Fixtures::lineItemHash());
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testIsCreatableViaAttributesArray()
    {
        $lineItem = $this->service->create([
            'name' => 'SDK Test',
            'status' => 'paused',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => Fixtures::campaignHash(),
        ]);

        $this->assertNotNull($lineItem->id);
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testIsCreatableViaResourceInstance()
    {
        $lineItem = new LineItem([
            'name' => 'SDK Test',
            'status' => 'paused',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => Fixtures::campaignHash(),
        ]);

        $lineItem = $this->service->create($lineItem);

        $this->assertNotNull($lineItem->id);
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testIsUpdateableViaAttributesArray()
    {
        $lineItem = $this->service->find(Fixtures::lineItemId());

        $uniqueName = uniqid('SDK_TEST_');

        $lineItem = $this->service->update([
            'id' => $lineItem->id,
            'version' => $lineItem->version,
            'name' => $uniqueName,
        ]);

        $this->assertEquals($uniqueName, $lineItem->name);
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testIsUpdateableViaResourceInstance()
    {
        $lineItem = $this->service->find(Fixtures::lineItemId());
        $uniqueName = uniqid('SDK_TEST_');

        $lineItem->name = $uniqueName;
        $lineItem = $this->service->update($lineItem);

        $this->assertEquals($uniqueName, $lineItem->name);
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }

    public function testWhatHappensWhenThereIsAnError()
    {
        // TODO move
        $this->expectException(InvalidRequestException::class);

        $this->service->create([
            'name' => 'SDK Test',
            'budget' => 0,
            'pacing' => 'even',
            'campaign' => Fixtures::campaignHash(),
        ]);
    }
}
