<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\SensorEngine\Http\Requests;

use OpenDialogAi\Core\Graph\DGraph\DGraphClient;
use OpenDialogAi\Xmpp\Tests\TestCase;

class IncomingXmppMessageTest extends TestCase
{
    protected $dGraph;

    protected function setUp(): void
    {
        parent::setUp();

        $this->activateConversation($this->conversation1());
        $this->activateConversation($this->conversation2());
        $this->activateConversation($this->conversation3());
        $this->activateConversation($this->conversation4());

        $this->dGraph = app()->make(DGraphClient::class);
    }

    protected function getData()
    {
        return [
            'notification' => 'message',
            'from' => 'user@example.com',
            'to' => 'user2@example.com',
            'lang' => 'en',
            'content' => [
                'type' => 'text',
                'author' => 'user@example.com',
                'data' => [
                    'text' => 'Some Message'
                ]
            ]
        ];
    }

    public function testNotificationTypeValidation()
    {
        $data = $this->getData();
        $data['notification'] = 'not-allowed';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'notification' => [
                    'The selected notification is invalid.'
                ]]]);
    }

    public function testFromAddressValidation()
    {
        $data = $this->getData();
        $data['from'] = $author = 'not-allowed@example';
        $data['content']['author'] = $author;

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'from' => [
                    'The from must be a valid email address.'
                ]]]);
    }

    public function testToAddressValidation()
    {
        $data = $this->getData();
        $data['to'] = 'fake-id';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'to' => [
                    'The to must be a valid email address.'
                ]]]);
    }

    public function testLanguageIsSupportedValidation()
    {
        $data = $this->getData();
        $data['lang'] = 'de';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'lang' => [
                    'The selected lang is invalid.'
                ]]]);
    }

    public function testContentIsRequiredInValidation()
    {
        $data = $this->getData();
        unset($data['content']);

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'content' => [
                    'The content field is required.'
                ]]]);
    }

    public function testContentTypeValidates()
    {
        $data = $this->getData();
        $data['content']['type'] = 'test';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'content.type' => [
                    'The selected content.type is invalid.'
                ]]]);
        ;
    }

    public function testContentAuthorValidates()
    {
        $data = $this->getData();
        $data['from'] = 'user@email.com';
        $data['content']['author'] = 'fake-id';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'content.author' => [
                    'The content.author and from must match.'
                ]
            ]]);
    }

    public function testContentDataValidation()
    {
        $data = $this->getData();
        unset($data['content']['data']);

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'content.data' => [
                    'The content.data field is required.'
                ]
            ]]);
    }

    public function testContentDataTextValidation()
    {
        $data = $this->getData();
        unset($data['content']['data']['text']);

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'content.data.text' => [
                    'The content.data.text field is required.'
                ]
            ]]);
    }

    public function testRequestCanPassValidation()
    {
        $data = $this->getData();

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(200);
    }
}
