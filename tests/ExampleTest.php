<?php

namespace Tests;

use LiveIntent\SDK\LiveIntentClient;
use LiveIntent\SDK\ApiResources\LineItem;

class ExampleTest extends TestCase
{
    public function testExampleStuff()
    {
        $li = new LiveIntentClient([
            'client_id' => 'ari',
            'client_secret' => '93f129a60f17264feab81a260256f13e'
        ]);

        $lineItem = $li->lineItems->find(192431);
        $this->assertInstanceOf(LineItem::class, $lineItem);
    }
}
