<?php

require './vendor/autoload.php';

use LiveIntent\SDK\LiveIntentClient;

// Find example

$li = new LiveIntentClient([
    'client_id' => 'ari',
    'client_secret' => '93f129a60f17264feab81a260256f13e'
]);

$lineItem = $li->lineItems->find(192431);

dd($lineItem->status());

dd($lineItem->id);
