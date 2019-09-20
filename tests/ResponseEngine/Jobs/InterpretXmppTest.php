<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\ResponseEngine\Jobs;

use Illuminate\Support\Facades\Bus;
use OpenDialogAi\Xmpp\Tests\TestCase;
use OpenDialogAi\Xmpp\ResponseEngine\Jobs\InterpretXmpp;

class InterpretXmppTest extends TestCase
{
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
}
