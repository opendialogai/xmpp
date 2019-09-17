<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp;

use OpenDialogAi\Core\Contracts\OpenDialogMessageContract;
use OpenDialogAi\ResponseEngine\Message\Webchat\WebChatMessage;

class XmppMessage implements OpenDialogMessageContract
{
    const TIME = 'time';

    const DATE = 'date';

    protected $messageType = 'text';

    /** The message text. */
    private $text = null;

    private $time;

    private $date;

    private $isEmpty = false;

    public function __construct()
    {
        $this->time = date('h:i A');
        $this->date = date('D j M');
    }

    /**
     * Sets text for a standard Web Chat message. The main text is escaped
     *
     * @param $format - main message text
     * @param array $args - replaced in format
     * @param bool $noSpecialChars
     * @return $this
     */
    public function setText($format, $args = [], bool $noSpecialChars = false)
    {
        if ($noSpecialChars) {
            $this->text = vsprintf($format, $args);
        } else {
            // Escape &, <, > characters
            $this->text = vsprintf(htmlspecialchars($format, ENT_NOQUOTES), $args);
        }
        return $this;
    }

    /**
     * @return null|string
     */
    public function getText():?string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->isEmpty;
    }

    public function setAsEmpty(): void
    {
        $this->isEmpty = true;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'text' => $this->getText(),
            self::TIME => $this->getTime(),
            self::DATE => $this->getDate()
        ];
    }

    public function getMessageToPost()
    {
        if ($this->isEmpty) {
            return false;
        }
        return [
            'author' => 'them',
            'type' => $this->getMessageType(),
            'data' => $this->getData()
        ];
    }
}
