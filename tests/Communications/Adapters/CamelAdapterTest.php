<?php

namespace OpenDialogAi\Xmpp\Tests\Communications\Adapters;

use GuzzleHttp\Client;
use OpenDialogAi\Xmpp\Communications\Adapters\CamelAdapter;
use OpenDialogAi\Xmpp\Tests\TestCase;

class CamelAdapterTest extends TestCase
{
    protected $adapter;

    public function setUp(): void
    {
        parent::setUp();

        $this->adapter = new CamelAdapter();
    }

    public function testAdapterCanSetUrl()
    {
        $this->assertEquals(null, $this->adapter->getUrl());

        $this->adapter->setUrl($url = 'www.example.com');

        $this->assertEquals($url, $this->adapter->getUrl());
    }

    public function testAdapterCanSetPort()
    {
        $this->assertEquals(null, $this->adapter->getPort());

        $this->adapter->setPort($port = 1234);

        $this->assertEquals($port, $this->adapter->getPort());
    }

    public function testAdapterCanSetPayload()
    {
        $this->assertEquals(null, $this->adapter->getPayload());

        $this->adapter->setPayload($payload = []);

        $this->assertEquals($payload, $this->adapter->getPayload());
    }

    public function testAdapterCanSetProtocol()
    {
        $this->assertEquals(null, $this->adapter->getProtocol());

        $this->adapter->setProtocol(null);

        $this->assertEquals('https', $this->adapter->getProtocol());

        $this->adapter->setProtocol('test');

        $this->assertEquals('test', $this->adapter->getProtocol());
    }

    public function testAdapterCanSetEndpoint()
    {
        $this->assertEquals(null, $this->adapter->getEndpoint());

        $this->adapter->setEndpoint($endpoint = 'some-endpoint');

        $this->assertEquals($endpoint, $this->adapter->getEndpoint());
    }

    public function testAdapterHasTheCorrectClient()
    {
        $this->assertEquals(null, $this->adapter->getClient());
        $this->adapter->setClient(new Client());
        $this->assertInstanceOf(Client::class, $this->adapter->getClient());
    }

    public function testAdapterCanBuildUrl()
    {
        $data = [
            'url' => 'www.example.com',
            'port' => 8080,
            'endpoint' => 'api/test',
            'protocol' => 'https',
            'payload' => [
                'foo' => 'bar'
            ]
        ];

        $this->adapter
            ->setUrl($data['url'])
            ->setPort($data['port'])
            ->setProtocol($data['protocol'])
            ->setEndpoint($data['endpoint'])
            ->setPayload($data['payload']);

        $this->assertEquals($data['url'], $this->adapter->getUrl());
        $this->assertEquals($data['port'], $this->adapter->getPort());
        $this->assertEquals($data['protocol'], $this->adapter->getProtocol());
        $this->assertEquals($data['endpoint'], $this->adapter->getEndpoint());
        $this->assertEquals($data['payload'], $this->adapter->getPayload());

        $url = sprintf(
            "%s://%s:%s/%s",
            $data['protocol'],
            $data['url'],
            $data['port'],
            $data['endpoint']
        );

        $this->assertEquals($url, $this->adapter->buildUri());
    }
}
