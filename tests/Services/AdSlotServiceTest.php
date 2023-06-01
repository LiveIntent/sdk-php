<?php

namespace Tests\Services;

use Tests\Fixtures;
use LiveIntent\AdSlot;
use LiveIntent\ResourceResponse;
use LiveIntent\ResourceServiceOptions;
use LiveIntent\Exceptions\InvalidRequestException;

class AdSlotServiceTest extends ServiceTestCase
{
    protected $serviceKey = 'adSlots';

    public function testIsFindable()
    {
        $adSlot = $this->service->find(Fixtures::adSlotId());
        $this->assertInstanceOf(AdSlot::class, $adSlot);

        $adSlot = $this->service->find(Fixtures::adSlotHash());
        $this->assertInstanceOf(AdSlot::class, $adSlot);
    }

    public function testIsCreatableViaAttributesArray()
    {
        $adSlot = $this->service->create([
            'name' => 'SDK Test',
            'newsletter' => Fixtures::newsletterHash(),
            'type' => 'image',
            'mediaType' => 'newsletter',
            'sizes' => [
                [
                    'width' => 500,
                    'height' => 600,
                    'floor' => 1.0,
                    'deviceTypes' => [1, 2, 3],
                ],
            ],
            'adIndicatorId' => 1,
        ]);

        $this->assertNotNull($adSlot->id);
        $this->assertInstanceOf(AdSlot::class, $adSlot);
    }

    public function testIsCreatableViaResourceInstance()
    {
        $adSlot = new AdSlot([
            'name' => 'SDK Test',
            'newsletter' => Fixtures::newsletterHash(),
            'type' => 'image',
            'mediaType' => 'newsletter',
            'sizes' => [
                [
                    'width' => 500,
                    'height' => 600,
                    'floor' => 1.0,
                    'deviceTypes' => [1, 2, 3],
                ],
            ],
            'adIndicatorId' => 1,
        ]);

        $adSlot = $this->service->create($adSlot);

        $this->assertNotNull($adSlot->id);
        $this->assertInstanceOf(AdSlot::class, $adSlot);
    }

    public function testIsUpdateableViaAttributesArray()
    {
        $adSlot = $this->service->find(Fixtures::adSlotId());

        $updatedName = 'SDK_TEST_UPDATE_NAME';

        $adSlot = $this->service->update([
            'id' => $adSlot->id,
            'version' => $adSlot->version,
            'name' => $updatedName,
            'sizes' => [
                [
                    'width' => 500,
                    'height' => 600,
                    'floor' => 1.0,
                    'deviceTypes' => [1, 2, 3],
                ],
            ],
        ]);

        $this->assertEquals($updatedName, $adSlot->name);
        $this->assertInstanceOf(AdSlot::class, $adSlot);
    }

    public function testIsUpdateableViaAttributesArrayAndCanReturnRawResponse()
    {
        $adSlot = $this->service->find(Fixtures::adSlotId());

        $updatedName = 'SDK_TEST_UPDATE_NAME';

        $options = new ResourceServiceOptions();
        $options->withRawResponse();

        $resp = $this->service->update(
            [
                'id' => $adSlot->id,
                'version' => $adSlot->version,
                'name' => $updatedName,
                'sizes' => [
                    [
                        'width' => 500,
                        'height' => 600,
                        'floor' => 1.0,
                        'deviceTypes' => [1, 2, 3],
                    ],
                ],
            ],
            $options,
        );

        $this->assertInstanceOf(ResourceResponse::class, $resp);
        $this->assertInstanceOf(AdSlot::class, $resp->resource);
        $this->assertEquals($updatedName, $resp->resource->name);
        $this->assertIsArray($resp->response->headers());
        $this->assertNotEmpty($resp->response->headers());
        $this->assertNotNull($resp->response->body());
    }

    public function testIsUpdateableViaResourceInstance()
    {
        $adSlot = $this->service->find(Fixtures::adSlotId());
        $updatedName = 'SDK_TEST_UPDATE_NAME';

        $adSlot->name = $updatedName;
        $adSlot = $this->service->update($adSlot);

        $this->assertEquals($updatedName, $adSlot->name);
        $this->assertInstanceOf(AdSlot::class, $adSlot);
    }

    public function testUpdateExternalUrl()
    {
        $adSlot = $this->service->find(Fixtures::adSlotId());
        $gamService = $this->createService('gam');
        $accountId = 123456;
        $externalUrl = $gamService->getGamUrl($adSlot->refId, $accountId);
        $adSlot->externalUrl = $externalUrl;
        $adSlot = $this->service->update($adSlot);
        $this->assertEquals($adSlot->externalUrl, $externalUrl);
    }

    public function testThrowsWhenInvalidDataIsPassed()
    {
        $this->expectException(InvalidRequestException::class);

        $this->service->create([
            'name' => 'SDK Test',
            'newsletter' => Fixtures::newsletterHash(),
        ]);
    }

    public function testExceptionNotThrownWhenKeepingRawResponse()
    {
        $options = new ResourceServiceOptions();
        $options->withRawResponse();

        $resp = $this->service->create([
            'name' => 'SDK Test',
            'newsletter' => Fixtures::newsletterHash(),
        ], $options);

        $this->assertInstanceOf(ResourceResponse::class, $resp);
        $this->assertNull($resp->resource);
        $this->assertIsArray($resp->response->headers());
        $this->assertNotEmpty($resp->response->headers());
        $this->assertNotNull($resp->response->body());
    }
}
