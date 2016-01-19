<?php

namespace CxInsightSDK\Document;

use CxInsightSDK\BaseSDK;

/*
 * Even though the path for this endpoint doesn't reflect the folder structure,
 * this class was added here to keep the organization of the search classes consistent.
 */
class Delete extends BaseSDK
{
    /**
     * @{inheritDoc}
     */
    protected $requestPath = '/profile/content/delete';
}
