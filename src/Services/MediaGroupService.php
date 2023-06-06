<?php

namespace LiveIntent\Services;

use LiveIntent\MediaGroup;

/**
 * @method \LiveIntent\MediaGroup|\LiveIntent\ResourceResponse find($id, $options = null)
 * @method \LiveIntent\MediaGroup|\LiveIntent\ResourceResponse create($attributes, $options = null)
 * @method \LiveIntent\MediaGroup|\LiveIntent\ResourceResponse update($attributes, $options = null)
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
