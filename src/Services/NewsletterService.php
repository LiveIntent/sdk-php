<?php

namespace LiveIntent\Services;

use LiveIntent\Newsletter;

/**
 * @method \LiveIntent\Newsletter find($id, $options = null)
 * @method \LiveIntent\Newsletter create($attributes, $options = null)
 * @method \LiveIntent\Newsletter update($attributes, $options = null)
 */
class NewsletterService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/newsletter';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = Newsletter::class;
}
