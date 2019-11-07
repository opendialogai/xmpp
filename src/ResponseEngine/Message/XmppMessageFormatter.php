<?php

namespace OpenDialogAi\Xmpp\ResponseEngine\Message;

use Exception;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\ResponseEngine\Formatters\BaseMessageFormatter;
use OpenDialogAi\ResponseEngine\Message\ButtonMessage;
use OpenDialogAi\ResponseEngine\Message\EmptyMessage;
use OpenDialogAi\ResponseEngine\Message\FormMessage;
use OpenDialogAi\ResponseEngine\Message\ImageMessage;
use OpenDialogAi\ResponseEngine\Message\ListMessage;
use OpenDialogAi\ResponseEngine\Message\LongTextMessage;
use OpenDialogAi\ResponseEngine\Message\OpenDialogMessage;
use OpenDialogAi\ResponseEngine\Message\OpenDialogMessages;
use OpenDialogAi\ResponseEngine\Message\RichMessage;
use OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp\XmppMessage;
use OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp\XmppMessages;
use SimpleXMLElement;

class XmppMessageFormatter extends BaseMessageFormatter
{
    public static $name = 'formatter.core.xmpp';

    /** @var array  */
    protected $messages = [];

    /**
     * @inheritDoc
     */
    public function getMessages(string $markup): OpenDialogMessages
    {
        $messages = [];
        try {
            $message = new SimpleXMLElement($markup);
            foreach ($message->children() as $item) {
                $messages[] = $this->parseMessage($item);
            }
        } catch (Exception $e) {
            Log::warning(sprintf('Message Builder error: %s', $e->getMessage()));
            return new XmppMessages();
        }

        $messageWrapper = new XmppMessages();
        foreach ($messages as $message) {
            $messageWrapper->addMessage($message);
        }

        return $messageWrapper;
    }

    public function generateTextMessage(array $template): OpenDialogMessage
    {
        $message = (new XmppMessage())->setText($template[self::TEXT], [], true);
        return $message;
    }

    public function generateButtonMessage(array $template): ButtonMessage
    {
        return null;
    }

    public function generateEmptyMessage(): EmptyMessage
    {
        return null;
    }

    public function generateFormMessage(array $template): FormMessage
    {
        return null;
    }

    public function generateImageMessage(array $template): ImageMessage
    {
        return null;
    }

    public function generateListMessage(array $template): ListMessage
    {
        return null;
    }

    public function generateLongTextMessage(array $template): LongTextMessage
    {
        return null;
    }

    public function generateRichMessage(array $template): RichMessage
    {
        return null;
    }

    /**
     * Parse XML markup and convert to the appropriate Message class.
     *
     * @param SimpleXMLElement $item
     * @return OpenDialogMessage
     */
    private function parseMessage(SimpleXMLElement $item)
    {
        switch ($item->getName()) {
            case self::TEXT_MESSAGE:
                $text = $this->getMessageText($item);
                $template = [self::TEXT => $text];
                return $this->generateTextMessage($template);
                break;
            default:
                $template = [self::TEXT => 'Sorry, I did not understand this message type.'];
                return $this->generateTextMessage($template);
                break;
        }
    }

    /**
     * @param SimpleXMLElement $element
     * @return string
     */
    protected function getMessageText(SimpleXMLElement $element): string
    {
        $dom = new \DOMDocument();
        $dom->loadXML($element->asXml());

        $text = '';
        foreach ($dom->childNodes as $node) {
            foreach ($node->childNodes as $item) {
                if ($item->nodeType === XML_TEXT_NODE) {
                    if (!empty(trim($item->textContent))) {
                        $text .= ' ' . trim($item->textContent);
                    }
                } elseif ($item->nodeType === XML_ELEMENT_NODE) {
                    if ($item->nodeName === self::LINK) {
                        Log::warning('Not adding link to message text, not supported on OD XMPP messages');
                    }
                }
            }
        }

        return trim($text);
    }
}
