<?php

namespace CxInsightSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class BaseSDK
{
    const BASE_URI = 'https://api.cxense.com';
    const AUTH_HEADER = 'x-cXense-Authentication';

    /**
     * Cxense API Username
     *
     * @var string
     */
    protected $username;

    /**
     * Cxense API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Cxense IDs of sites to fetch data for
     *
     * @var array
     */
    protected $siteIds;

    /**
     * API endpoint to request data from
     *
     * @var string
     */
    protected $requestPath = '';

    /**
     * GuzzleHttp client
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    public function __construct($username, $apiKey, array $siteIds)
    {
        $this->username = $username;
        $this->apiKey = $apiKey;
        $this->siteIds = $siteIds;
    }

    /**
     * Wrapper around the runRequest method, to fetch the Cxense data.
     *
     * @param  array  $options Request Options
     * @return
     */
    public function getData($options = [])
    {
        return $this->runRequest($options);
    }

    /**
     * Returns the client. Client is created if it is not yet set.
     *
     * @return GuzzleHttp\Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(['base_uri' => self::BASE_URI]);
        }

        return $this->client;
    }

    /**
     * Run the request with the given options
     *
     * @param  array $options Request Options See corresponding API documentation for details on available options
     * @return array
     */
    protected function runRequest($options = [])
    {
        if ($this->requestPath == '') {
            throw new \Exception('requestPath must be specified.');
        }

        $client = $this->getClient();

        // Add siteGroupId and siteIds to the $options array
        $options['siteIds'] = $this->siteIds;

        $response = $client->post(
            $this->requestPath,
            [
                'body' => json_encode($options),
                'headers' => [
                    self::AUTH_HEADER => $this->generateAuthHeaderValue()
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Create the authentication header value needed when sending
     * requests to the Cxense Insight API
     *
     * @return string
     */
    protected function generateAuthHeaderValue()
    {
        $date = date('Y-m-d\TH:i:s.000O');
        $signature = hash_hmac('sha256', $date, $this->apiKey);

        return "username=$this->username date=$date hmac-sha256-hex=$signature";
    }
}
