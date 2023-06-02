<?php

namespace LiveIntent\Services;

use LiveIntent\MediaGroup;

/**
 * @method \LiveIntent\MediaGroup find($id, $options = null)
 * @method \LiveIntent\MediaGroup create($attributes, $options = null)
 * @method \LiveIntent\MediaGroup update($attributes, $options = null)
 */
class MediaGroupService extends AbstractResourceService
{
    /**
     * The base url for this entity.
     */
    protected $baseUrl = '/media-group';

    /**
     * The resource class for this entity.
     */
    protected $objectClass = MediaGroup::class;
}
