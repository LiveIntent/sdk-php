<?php

namespace LiveIntent;

use LiveIntent\Client\AbstractClient;

/**
 * Client used to interact with the LiveIntent Api.
 *
 * @property \LiveIntent\Services\AdSlotService $adSlots
 * @property \LiveIntent\Services\AdvertiserService $advertisers
 * @property \LiveIntent\Services\AuthService $auth
 * @property \LiveIntent\Services\CampaignService $campaigns
 * @property \LiveIntent\Services\InsertionOrderService $insertionOrders
 * @property \LiveIntent\Services\LineItemService $lineItems
 * @property \LiveIntent\Services\MediaGroupService $mediaGroups
 * @property \LiveIntent\Services\NewsletterService $newsletters
 * @property \LiveIntent\Services\PublisherService $publishers
 * @property \LiveIntent\Services\UserService $users
 *
 * @property \LiveIntent\Services\BaseService $request
 */
class LiveIntentClient extends AbstractClient
{
    /**
     * The global client options.
     *
     * @var array
     */
    protected $options = [
        // The base URL for all api requests
        'base_url' => null,

        // The number of times a request should be tried
        'tries' => 1,

        // The number of seconds to wait before retrying a request
        'retryDelay' => 100,

        // The number of seconds to wait for a response before hanging up
        'timeout' => 10,
    ];
}
