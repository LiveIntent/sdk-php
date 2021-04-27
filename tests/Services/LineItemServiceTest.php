<?php

namespace Tests\Services;

use LiveIntent\SDK\ApiResources\LineItem;
use LiveIntent\SDK\Services\LineItemService;
use LiveIntent\SDK\Exceptions\ResourceNotFoundException;

class LineItemServiceTest extends ServiceTestCase
{
    /**
     * The service under test.
     *
     * @var \LiveIntent\SDK\Services\AbstractService
     */
    protected $serviceClass = LineItemService::class;

    /** @test */
    public function line_items_are_retrievable_by_ref_id()
    {
        $lineItem = $this->service->find(192431);

        $this->assertInstanceOf(LineItem::class, $lineItem);
        $this->assertEquals('00009758365a11e7943622000a974651', $lineItem->id);
        $this->assertEquals(192431, $lineItem->refId);
    }

    /** @test */
    public function line_items_are_retrievable_by_hash_id()
    {
        $lineItem = $this->service->find('00009758365a11e7943622000a974651');

        $this->assertInstanceOf(LineItem::class, $lineItem);
        $this->assertEquals('00009758365a11e7943622000a974651', $lineItem->id);
        $this->assertEquals(192431, $lineItem->refId);
    }

    /** @test */
    public function line_items_that_could_not_be_resolved_return_null()
    {
        $lineItem = $this->service->find('abc');

        $this->assertNull($lineItem);
    }

    /** @test */
    public function find_or_fail_throws_an_exception()
    {
        $this->expectException(ResourceNotFoundException::class);

        $this->service->findOrFail('abc');
    }
}
