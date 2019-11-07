<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\ResponseEngine\Jobs;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use OpenDialogAi\Core\Attribute\StringAttribute;
use OpenDialogAi\Core\Graph\DGraph\DGraphClient;
use OpenDialogAi\Xmpp\Communications\Adapters\CamelAdapter;
use OpenDialogAi\Xmpp\Communications\CommunicationServiceInterface;
use OpenDialogAi\Xmpp\ResponseEngine\Jobs\InterpretXmpp;
use OpenDialogAi\Xmpp\Tests\TestCase;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class InterpretXmppTest extends TestCase
{
    protected $dGraph;

    protected function setUp(): void
    {
        parent::setUp();

        $attributes = ['test' => StringAttribute::class];

        $this->setCustomAttributes($attributes);

        $this->activateConversation($this->conversation1());
        $this->activateConversation($this->conversation2());
        $this->activateConversation($this->conversation3());
        $this->activateConversation($this->conversation4());

        $this->dGraph = app()->make(DGraphClient::class);
    }

    protected function getData()
    {
        return [
            'notification' => 'message',
            'from' => $author = 'user1@example.com',
            'to' => 'user2@xmpp-server.opendialog.ai',
            'lang' => 'en',
            'content' => [
                'type' => 'text',
                'author' => $author,
                'data' => [
                    'text' => 'Some Message'
                ]
            ]
        ];
    }

    public function testJobIsDispatched()
    {
        Bus::fake();

        Bus::assertNotDispatched(InterpretXmpp::class);

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data = $this->getData()
        );

        $response->assertStatus(200);

        Bus::assertDispatched(InterpretXmpp::class);
    }

    public function testUtteranceIsCorrectType()
    {
        Queue::fake();

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data = $this->getData()
        );

        $response->assertStatus(200);

        // Make sure correct utterance is created.
        Queue::assertPushed(InterpretXmpp::class, function ($job) {
            return $job->utterance instanceof TextUtterance;
        });
    }

    public function testSending()
    {
        $adapterMock = $this->spy(CamelAdapter::class, function (MockInterface $mock) {
            $mock->shouldReceive('setPayload')->once();
        });

        $this->mock(CommunicationServiceInterface::class, function (MockInterface $mock) use ($adapterMock) {
            $mock->shouldReceive('getAdapter')->once()->andReturn($adapterMock);
            $mock->shouldReceive('communicate')->once()->andReturn(new Response());
        });

        $this->json(
            'post',
            '/incoming/xmpp',
            $data = $this->getData()
        );
    }
}
