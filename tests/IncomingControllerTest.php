<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests;

class IncomingControllerTest extends TestCase
{
    public function testATestRoute()
    {
        $response = $this->get('/incoming/test');

        $response->assertStatus(200);
    }

    public function testNotificationTypeValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'notification' => 'not-allowed'
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'notification' => [
                    'The selected notification is invalid.'
                ]]]);
    }

    public function testFromAddressValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'from' => 'user@not-allowed.com'
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'from' => [
                    'The from address must be a correctly formed Open Dialog XMPP address.'
                ]]]);
    }

    public function testToAddressValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'to' => 'user@not-allowed.com'
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'to' => [
                    'The to address must be a correctly formed Open Dialog XMPP address.'
                ]]]);
    }

    public function testLanguageIsSupportedValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'lang' => 'de'
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'lang' => [
                    'The selected lang is invalid.'
                ]]]);
    }

    public function testRequestCanPassValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'notification' => 'message',
            'from' => 'user1@@xmpp-server.opendialog.ai',
            'to' => 'user2@xmpp-server.opendialog.ai',
            'lang' => 'en'
        ]);

        $response->assertStatus(200);
    }
}
