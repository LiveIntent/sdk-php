<?php

namespace LiveIntent\Services;

/**
 * @method string|null createGamUrl($adSlotId, $googleAccountId)
 */
class GamService extends BaseService
{
    /**
     * Get Gam Url for AdSlot
     *
     * @param int $adSlotId
     * @param string|int $googleAccountId
     * @return string|null
     */
    public function getGamUrl($adSlotId, $googleAccountId)
    {
        $url = "/google-ad-manager/ad-slot-gam-url/{$adSlotId}/{$googleAccountId}";

        return data_get($this->request('get', $url), 'output.gamUrl');
    }
}
