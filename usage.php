<?php

require './vendor/autoload.php';

use LiveIntent\LiveIntentClient;
use LiveIntent\Services\LineItemService;

// Find example

$li = new LiveIntentClient([
    'client_id' => 'ari',
    'client_secret' => '93f129a60f17264feab81a260256f13e'
]);

$lineItem = $li->lineItems->find(192431);


$service = new LineItemService($li);
$lineItem = $service->find(192431);
// dd($lineItem->id, $lineItem->refId);

// {
//   "access_token": "kIXI7TbqPccwusQjaW/yutbr/gv6+xyy",
//   "token_type": "Bearer",
//   "expires_in": 3599,
//   "id_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjQwOTAsImV4cCI6MTYxOTQ2Mjk4NywiaWF0IjoxNjE5NDU5Mzg3LCJhdWQiOiJhcmkiLCJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjMwMDAifQ.N3VNInq5YtdVHtbD8XKp0zmTOtdcpJAa42A4-Ymgqn4"
// }
