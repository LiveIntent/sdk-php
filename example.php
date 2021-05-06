<?php

use LiveIntent\LiveIntentClient;

$client = new LiveIntentClient();

$liveintent->auth->user();

$liveintent->lineItems->find(1);

$liveIntent->auth->withOption()->user();

$liveIntent->lineItems->withOption()->find();



$liveintent = new Client();

class Service
{
    $request;

    /**
     *
     */
    public function user()
    {
        if (!$this->request) {
            $this->request = $illuminateFactory->newPendingRequest();
            // mutate the request with default opts
        }

        $this->request->send('hihi');
    }
}

class Client
{
    public $auth = new Service();
}
