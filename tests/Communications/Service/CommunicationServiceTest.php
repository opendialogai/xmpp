<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\Communications\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use OpenDialogAi\Xmpp\Communications\Adapters\CamelAdapter;
use OpenDialogAi\Xmpp\Communications\CommunicationInterface;
use OpenDialogAi\Xmpp\Communications\Service\CommunicationService;
use OpenDialogAi\Xmpp\Tests\TestCase;

class CommunicationServiceTest extends TestCase
{
    protected $client;

    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(201, ['X-Foo' => 'Bar'])
        ]);

        $handler = HandlerStack::create($mock);
        $this->client =  new Client(['handler' => $handler]);
        $this->service = new CommunicationService(
            new CamelAdapter()
        );

        $this->service->getAdapter()->setClient($this->client);
    }

    protected function buildServiceAdapter(): array
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

        $this->service->build($data);

        return $data;
    }

    public function testServiceCanBuildAdapter()
    {
        $data = $this->buildServiceAdapter();
        $this->assertInstanceOf(CamelAdapter::class, $this->service->getAdapter());
        $this->assertInstanceOf(CommunicationInterface::class, $this->service->getAdapter());

        $adapter = $this->service->getAdapter();
        $url = sprintf(
            "%s://%s::%s/%s",
            $data['protocol'],
            $data['url'],
            $data['port'],
            $data['endpoint']
        );

        $this->assertEquals($url, $adapter->buildUri());
    }

    public function testServiceCanCommunicate()
    {
        $data = $this->buildServiceAdapter();

        $response = $this->service->communicate();

        $this->assertEquals(201, $response->getStatusCode());
    }
}
