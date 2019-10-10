<?php

namespace OpenDialogAi\XMPP\Tests\DataTransferObjects;

use OpenDialogAi\Xmpp\DataTransferObjects\XmppDTO;
use OpenDialogAi\Xmpp\Tests\TestCase;

class XmppDTOTest extends TestCase
{
    /** @var XmppDTO */
    protected $dto;

    public function setUp(): void
    {
        parent::setUp();

        $this->dto = new XmppDTO();
    }

    public function testNotificationCanBeSet()
    {
        $this->assertNull($this->dto->getNotification());

        $this->dto->setNotification($value = 'message');

        $this->assertEquals($value, $this->dto->getNotification());

        $result = $this->dto->toArray();

        $this->assertEquals($value, $result['notification']);
    }

    public function testFromCanBeSet()
    {
        $this->assertNull($this->dto->getFrom());

        $this->dto->setFrom($value = 'user@email.com');

        $this->assertEquals($value, $this->dto->getFrom());

        $result = $this->dto->toArray();

        $this->assertEquals($value, $result['from']);
    }

    public function testToCanBeSet()
    {
        $this->assertNull($this->dto->getTo());

        $this->dto->setTo($value = 'user@email.com');

        $this->assertEquals($value, $this->dto->getTo());

        $result = $this->dto->toArray();

        $this->assertEquals($value, $result['to']);
    }

    public function testLanguageCanBeSet()
    {
        $this->assertNull($this->dto->getLanguage());

        $this->dto->setLanguage($value = 'en');

        $this->assertEquals($value, $this->dto->getLanguage());

        $result = $this->dto->toArray();

        $this->assertEquals($value, $result['lang']);
    }

    public function testContentTypeCanBeSet()
    {
        $this->assertNull($this->dto->getContentType());

        $this->dto->setContentType($value = 'text');

        $this->assertEquals($value, $this->dto->getContentType());

        $result = $this->dto->toArray();

        $this->assertEquals($value, $result['content']['type']);
    }

    public function testContentDataCanBeSet()
    {
        $this->assertNull($this->dto->getContentData());

        $this->dto->setContentData($value = 'This is a message');

        $this->assertEquals($value, $this->dto->getContentData());

        $result = $this->dto->toArray();

        $this->assertEquals($value, $result['content']['data']['text']);
    }
}
