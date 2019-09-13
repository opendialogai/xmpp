<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\Http\Requests;

use OpenDialogAi\Xmpp\Tests\TestCase;

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

    public function testContentIsRequiredInValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'content' => null
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'content' => [
                    'The content field is required.'
                ]]]);
    }

    protected function fakeContent()
    {
        $content = new \stdClass();
        $content->type = 'text';

        return $content;
    }

    public function testRequestCanPassValidation()
    {
        $response = $this->json('post', '/incoming/xmpp', [
            'notification' => 'message',
            'from' => 'user1@@xmpp-server.opendialog.ai',
            'to' => 'user2@xmpp-server.opendialog.ai',
            'lang' => 'en',
            'content' => json_encode($this->fakeContent())
        ]);

        $response->assertStatus(200);
    }
}
