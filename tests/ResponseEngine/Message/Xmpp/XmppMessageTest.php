<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\ResponseEngine\Message\Xmpp;

use OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp\XmppMessage;
use OpenDialogAi\Xmpp\Tests\TestCase;
use OpenDialogAi\Xmpp\ResponseEngine\Message\XmppMessageFormatter;

class XmppMessageTest extends TestCase
{
    public function testTextMessage()
    {
        $markup = '<message disable_text="1"><text-message>hi there</text-message></message>';
        $formatter  = new XmppMessageFormatter();

        /** @var XmppMessage[] $messages */
        $messages = $formatter->getMessages($markup)->getMessages();
        $this->assertEquals('hi there', $messages[0]->getText());
        $markup = <<<EOT
<message disable_text="0">
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
