<?php

namespace OpenDialogAi\Xmpp\Tests\ResponseEngine\Message\Xmpp;

use OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp\XmppMessage;
use OpenDialogAi\Xmpp\Tests\TestCase;
use OpenDialogAi\Xmpp\ResponseEngine\Message\XmppMessageFormatter;

class XmppMessageTest extends TestCase
{
    /**
     * @requires DGRAPH
     */
    public function testTextMessage()
    {
        $markup = '<message><text-message>hi there</text-message></message>';
        $formatter  = new XmppMessageFormatter();

        /** @var XmppMessage[] $messages */
        $messages = $formatter->getMessages($markup)->getMessages();

        $this->assertEquals('hi there', $messages[0]->getText());
        $markup = <<<EOT
<message>
  <text-message>
    hi there
  </text-message>
</message>
EOT;
        $formatter  = new XmppMessageFormatter();

        $messages = $formatter->getMessages($markup)->getMessages();
        $this->assertEquals('hi there', $messages[0]->getText());
    }
}
