<?php

namespace LiveIntent\Services;

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
