<?php

namespace LiveIntent\Services;

/**
 * @method \LiveIntent\User user()
 */
class AuthService extends AbstractService
{
    /**
     *
     */
    public function user()
    {
        return $this->request('get', 'me');
    }
}
