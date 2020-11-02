<?php

namespace OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp;

use OpenDialogAi\ResponseEngine\Message\OpenDialogMessage;
use OpenDialogAi\ContextEngine\Facades\ContextService;
use OpenDialogAi\Xmpp\Helper\UserHelper;

class XmppMessage implements OpenDialogMessage
{
    public const TIME = 'time';

    public const DATE = 'date';

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
     * Sets text for a standard XMPP message. The main text is escaped
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
    public function getText(): ?string
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
    public function getData(): ?array
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
        $userId = UserHelper::getUserId(ContextService::getUserContext()->getUserId());

        return [
            'author' => config('opendialog.xmpp.bot_address'),
            'recipient' => $userId[UserHelper::ID_USER],
            'room' => array_key_exists('room', $userId) ? $userId[UserHelper::ROOM] : null,
            'type' => $this->getMessageType(),
            'data' => $this->getData()
        ];
    }

    public function setHidetime()
    {
        return;
    }

    public function setInternal()
    {
        return;
    }

    public function setIntent(string $intent): OpenDialogMessage
    {
        $this->intent = $intent;

        return $this;
    }
}
