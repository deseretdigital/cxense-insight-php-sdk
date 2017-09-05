<?php

namespace CxInsightSDK\Document;

use CxInsightSDK\BaseSDK;

class Search extends BaseSDK
{
    /**
     * @{inheritDoc}
     */
    protected $requestPath = '/document/search';

    /**
     * Handles runnning a search request. Currently moves debug from options
     * to query parameters
     *
     * @param array $options
     *
     * @return array
     */
    protected function runRequest($options = [])
    {
        $queryParameters = [];

        if ($options['debug']) {
            $queryParameters['debug'] = $options['debug'];
            // cXense freaks out if you pass debug in as an option so unset it
            unset($options['debug']);
        }

        $originalPath = $this->requestPath;
        $this->requestPath = $this->requestPath . '?' . http_build_query($queryParameters);
        $result = parent::runRequest($options);
        $this->requestPath = $originalPath;

        return $result;
    }
}
