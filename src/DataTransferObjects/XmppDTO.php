<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\DataTransferObjects;

use OpenDialogAi\Core\Contracts\DataTransferObjectInterface;

class XmppDTO implements DataTransferObjectInterface
{
    /**
     * @var string
     */
    private $notification;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $type;

    public function setNotification(string $value): self
    {
        $this->notification = $value;

        return $this;
    }

    public function getNotification():?string
    {
        return $this->notification;
    }

    public function setFrom(string $value): self
    {
        $this->from = $value;

        return $this;
    }

    public function getFrom():?string
    {
        return $this->from;
    }

    public function setTo(string $value): self
    {
        $this->to = $value;

        return $this;
    }

    public function getTo():?string
    {
        return $this->to;
    }

    public function setLanguage(string $value): self
    {
        $this->lang = $value;

        return $this;
    }

    public function getLanguage():?string
    {
        return $this->lang;
    }

    public function setContentType(string $value): self
    {
        $this->type = $value;

        return $this;
    }

    public function getContentType():?string
    {
        return $this->type;
    }

    public function setContentData(string $value): self
    {
        $this->data = $value;

        return $this;
    }

    public function getContentData():?string
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'notification' => $this->notification,
            'from' => $this->from,
            'to' => $this->to,
            'lang' => $this->lang,
            'content' => [
                'type' => $this->type,
                'author' => $this->from,
                'data' => [
                    'text' => $this->data
                ]
            ]
        ];
    }
}
