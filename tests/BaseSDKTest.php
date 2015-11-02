<?php

namespace CxInsightSDK\Tests;

use CxInsightSDK\Tests\Classes\TestSDK;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class BaseSDKTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetData()
    {
        // Setup the test client

        // Create a mock and queue a responses.
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $this->getResponseData(true)
            )
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $mockSDK = Mockery::mock(
            'CxInsightSDK\Tests\Classes\TestSDK',
            [
                'username',
                'apikey',
                [
                    'siteId'
                ]
            ]
        )
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $mockSDK->shouldReceive('getClient')
            ->once()
            ->andReturn($client);

        $actual = $mockSDK->getData();

        $this->assertEquals($this->getResponseData(), $actual);
    }

    /** @expectedException \Exception */
    public function testGetDataNoRequestPath()
    {
        $mockSDK = Mockery::mock('CxInsightSDK\BaseSDK')->makePartial();

        $actual = $mockSDK->getData();
    }

    public function testGetClient()
    {
        $testSDK = new TestSDK(
            'username',
            'apikey',
            [
                'siteId'
            ]
        );
        ddd(get_class_methods($testSDK));
        $client = $testSDK->getClient();

        $this->assertInstanceOf('GuzzleHttp\Client', $client);
    }

    protected function getResponseData($asJsonString = false)
    {
        $responseData = [
            'start' => 1444342209,
            'stop' => 1444947009,
            'data' => [
                'events' => 6168,
                'uniqueUsers' => 4293,
                'activeTime' => 28,
                'sessionStarts' => 4044,
                'sessionStops' => 2932,
                'sessionBounces' => 2160,
                'urls' => 1
            ],
        ];

        if ($asJsonString) {
            $responseData = json_encode($responseData);
        }

        return $responseData;
    }
}
