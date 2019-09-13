<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\Http\Requests;

use OpenDialogAi\Xmpp\Tests\TestCase;

class IncomingXmppMessageTest extends TestCase
{
    protected function getData()
    {
        return [
            'notification' => 'message',
            'from' => $author = 'user1@@xmpp-server.opendialog.ai',
            'to' => 'user2@xmpp-server.opendialog.ai',
            'lang' => 'en',
            'content' => [
                'type' => 'text',
                'author' => $author,
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
        $data['from'] = 'not-allowed@example.com';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'from' => [
                    'The from address must be a correctly formed Open Dialog XMPP address.'
                ]]]);
    }

    public function testToAddressValidation()
    {
        $data = $this->getData();
        $data['to'] = 'not-allowed@example.com';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'to' => [
                    'The to address must be a correctly formed Open Dialog XMPP address.'
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
        $data['from'] = $author = 'not-allowed@example.com';

        $response = $this->json(
            'post',
            '/incoming/xmpp',
            $data
        );

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'from' => [
                    'The from address must be a correctly formed Open Dialog XMPP address.'
                ],
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
