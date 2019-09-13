<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\Jobs;

use Illuminate\Support\Facades\Bus;
use OpenDialogAi\Xmpp\Jobs\InterpretXmpp;
use OpenDialogAi\Xmpp\Tests\TestCase;

class InterpretXmppTest extends TestCase
{
    protected function getData()
    {
        return [
            'notification' => 'message',
            'from' => $author = 'user1@@xmpp-server.opendialog.ai',
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

    public function testJobIsHandled()
    {
        Bus::fake();

        Bus::assertNotDispatched(InterpretXmpp::class);

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $this->getData()
        );

        $response->assertStatus(200);

        Bus::assertDispatched(InterpretXmpp::class);
    }
}
