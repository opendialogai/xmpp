<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\ResponseEngine\Message;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\ContextEngine\ContextParser;
use OpenDialogAi\ContextEngine\Facades\ContextService;
use OpenDialogAi\ResponseEngine\Message\MessageFormatterInterface;
use OpenDialogAi\ResponseEngine\Message\OpenDialogMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\ButtonMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\EmptyMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\FormMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\ImageMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\ListMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\LongTextMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\RichMessage;
use OpenDialogAi\ResponseEngine\Message\Webchat\WebChatMessage;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineService;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineServiceInterface;
use SimpleXMLElement;

class XmppMessageFormatter implements MessageFormatterInterface
{
    /** @var ResponseEngineService */
    private $responseEngineService;

    /** @var array  */
    protected $messages = [];

    /**
     * WebChatMessageFormatter constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->responseEngineService = app()->make(ResponseEngineServiceInterface::class);
    }

    public function getMessages(string $markup): array
    {
        $messages = [];
        try {
            $message = new SimpleXMLElement($markup);

            foreach ($message->children() as $item) {
                if ($item->getName() === self::ATTRIBUTE_MESSAGE) {
                    $attributeText = $this->getAttributeMessageText((string)$item);
                    return $this->getMessages($attributeText);
                }
                $messages[] = $this->parseMessage($item);
            }

            if (isset($message[self::DISABLE_TEXT])) {
                if ($message[self::DISABLE_TEXT] == '1' || $message[self::DISABLE_TEXT] == 'true') {
                    foreach ($messages as $webChatMessage) {
                        $webChatMessage->setDisableText(true);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning(sprintf('Message Builder error: %s', $e->getMessage()));
            return [];
        }

        return $messages;
    }

    /**
     * Resolves the attribute by name to get the value for the attribute message, then resolves any attributes
     * in the resulting text
     *
     * @param string $attributeName
     * @return string
     */
    protected function getAttributeMessageText($attributeName): string
    {
        [$contextId, $attributeId] = ContextParser::determineContextAndAttributeId($attributeName);
        $attributeValue = ContextService::getAttributeValue($attributeId, $contextId);

        return $this->responseEngineService->fillAttributes($attributeValue);
    }

    public function generateTextMessage(array $template): OpenDialogMessage
    {
        $message = (new OpenDialogMessage())->setText($template[self::TEXT], [], true);

        return $message;
    }

    public function generateButtonMessage(array $template): ButtonMessage
    {
        //
    }

    public function generateEmptyMessage(): EmptyMessage
    {
        //
    }

    public function generateFormMessage(array $template): FormMessage
    {
        //
    }

    public function generateImageMessage(array $template): ImageMessage
    {
        //
    }

    public function generateListMessage(array $template): ListMessage
    {
        //
    }

    public function generateLongTextMessage(array $template): LongTextMessage
    {
        //
    }

    public function generateRichMessage(array $template): RichMessage
    {
        //
    }

    /**
     * Parse XML markup and convert to the appropriate Message class.
     *
     * @param SimpleXMLElement $item
     * @return WebChatMessage
     */
    private function parseMessage(SimpleXMLElement $item)
    {
        switch ($item->getName()) {
            case self::BUTTON_MESSAGE:
                $template = $this->formatButtonTemplate($item);
                return $this->generateButtonMessage($template);
                break;
            case self::IMAGE_MESSAGE:
                $template = $this->formatImageTemplate($item);
                return $this->generateImageMessage($template);
                break;
            case self::LIST_MESSAGE:
                $template = $this->formatListTemplate($item);
                return $this->generateListMessage($template);
                break;
            case self::TEXT_MESSAGE:
                $text = $this->getMessageText($item);
                $template = [self::TEXT => $text];
                return $this->generateTextMessage($template);
                break;
            case self::RICH_MESSAGE:
                $template = $this->formatRichTemplate($item);
                return $this->generateRichMessage($template);
                break;
            case self::FORM_MESSAGE:
                $template = $this->formatFormTemplate($item);
                return $this->generateFormMessage($template);
                break;
            case self::LONG_TEXT_MESSAGE:
                $template = $this->formatLongTextTemplate($item);
                return $this->generateLongTextMessage($template);
                break;
            case self::EMPTY_MESSAGE:
                return new EmptyMessage();
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
                        $openNewTab = ($item->getAttribute('new_tab')) ? true : false;

                        $link = [
                            self::OPEN_NEW_TAB => $openNewTab,
                            self::TEXT => '',
                            self::URL => '',
                        ];

                        foreach ($item->childNodes as $t) {
                            $link[$t->nodeName] = trim($t->nodeValue);
                        }

                        if ($link[self::URL]) {
                            $text .= ' ' . $this->generateLinkHtml(
                                $link[self::URL],
                                $link[self::TEXT],
                                $link[self::OPEN_NEW_TAB]
                            );
                        } else {
                            Log::debug('Not adding link to message text, url is empty');
                        }
                    }
                }
            }
        }

        return trim($text);
    }
}
