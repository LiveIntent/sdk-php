<?php

namespace LiveIntent\Services;

use LiveIntent\User;

/**
 * @method \LiveIntent\LineItem find($id, $options = null)
 * @method \LiveIntent\LineItem create($attributes, $options = null)
 * @method \LiveIntent\LineItem update($attributes, $options = null)
 */
class UserService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/auth/user';

    /**
     * The base url for searches for this entity.
     */
    protected $searchUrl = '/search/user';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = User::class;
}
