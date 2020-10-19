<?php

namespace OpenDialogAi\Xmpp\Tests\Feature;

use OpenDialogAi\Core\Controllers\OpenDialogController;
use OpenDialogAi\Xmpp\Tests\TestCase;
use OpenDialogAi\Xmpp\Tests\UtteranceGenerator;

class XmppConversationsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // this is the NoMatch conversation
        $this->activateConversation($this->conversation4());
    }

    /**
     * @requires DGRAPH
     */
    public function testNoMatchConversation()
    {
        // This utterance will not match a conversation, so should trigger the no match message
        $utterance = UtteranceGenerator::generateTextUtterance('hello');

        $messages = resolve(OpenDialogController::class)->runConversation($utterance);

        // Here we can check that we got the right message out
        $this->assertNotNull($messages);
    }
}
