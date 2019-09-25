<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\ResponseEngine\Jobs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use OpenDialogAi\Core\Attribute\StringAttribute;
use OpenDialogAi\Core\Graph\DGraph\DGraphClient;
use OpenDialogAi\Xmpp\SensorEngine\Sensors\XmppSensor;
use OpenDialogAi\Xmpp\Tests\TestCase;
use OpenDialogAi\Xmpp\ResponseEngine\Jobs\InterpretXmpp;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class InterpretXmppTest extends TestCase
{
    protected $dGraph;

    protected function setUp(): void
    {
        parent::setUp();

        $attributes = ['test' => StringAttribute::class];

        $this->setCustomAttributes($attributes);

        $this->publishConversation($this->conversation1());
        $this->publishConversation($this->conversation2());
        $this->publishConversation($this->conversation3());
        $this->publishConversation($this->conversation4());

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
}
