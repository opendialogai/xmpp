<?php

namespace OpenDialogAi\Xmpp\ResponseEngine\Message;

use Exception;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\ResponseEngine\Formatters\BaseMessageFormatter;
use OpenDialogAi\ResponseEngine\Message\AutocompleteMessage;
use OpenDialogAi\ResponseEngine\Message\ButtonMessage;
use OpenDialogAi\ResponseEngine\Message\DatePickerMessage;
use OpenDialogAi\ResponseEngine\Message\EmptyMessage;
use OpenDialogAi\ResponseEngine\Message\FormMessage;
use OpenDialogAi\ResponseEngine\Message\FullPageFormMessage;
use OpenDialogAi\ResponseEngine\Message\FullPageRichMessage;
use OpenDialogAi\ResponseEngine\Message\HandToSystemMessage;
use OpenDialogAi\ResponseEngine\Message\ImageMessage;
use OpenDialogAi\ResponseEngine\Message\ListMessage;
use OpenDialogAi\ResponseEngine\Message\LongTextMessage;
use OpenDialogAi\ResponseEngine\Message\MetaMessage;
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

    public function generateAutocompleteMessage(array $template): AutocompleteMessage
    {
        // TODO: Implement generateAutocompleteMessage() method.
    }

    public function generateFullPageFormMessage(array $template): FullPageFormMessage
    {
        // TODO: Implement generateFullPageFormMessage() method.
    }

    public function generateMetaMessage(array $template): MetaMessage
    {
        // TODO: Implement generateMetaMessage() method.
    }

    public function generateFullPageRichMessage(array $template): FullPageRichMessage
    {
        // TODO: Implement generateFullPageRichMessage() method.
    }

    public function generateHandToSystemMessage(array $template): HandToSystemMessage
    {
        // TODO: Implement generateHandToSystemMessage() method.
    }

    public function generateDatePickerMessage(array $template): DatePickerMessage
    {
        // TODO: Implement generateDatePickerMessage() method.
    }

    public function generateButtonMessage(array $template): ButtonMessage
    {
        // TODO: Implement generateButtonMessage() method.
    }

    public function generateEmptyMessage(): EmptyMessage
    {
        // TODO: Implement generateEmptyMessage() method.
    }

    public function generateFormMessage(array $template): FormMessage
    {
        // TODO: Implement generateFormMessage() method.
    }

    public function generateImageMessage(array $template): ImageMessage
    {
        // TODO: Implement generateImageMessage() method.
    }

    public function generateListMessage(array $template): ListMessage
    {
        // TODO: Implement generateListMessage() method.
    }

    public function generateLongTextMessage(array $template): LongTextMessage
    {
        // TODO: Implement generateLongTextMessage() method.
    }

    public function generateRichMessage(array $template): RichMessage
    {
        // TODO: Implement generateRichMessage() method.
    }
}
