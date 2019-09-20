<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\SensorEngine\Sensors;

use Illuminate\Http\Request;
use OpenDialogAi\Core\Utterances\Exceptions\UtteranceUnknownMessageType;
use OpenDialogAi\Xmpp\SensorEngine\Sensors\XmppSensor;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class XmppSensorTest extends XmppSensorTestBase
{
    /**
     * @var XmppSensor
     */
    private $sensor;

    public function setUp(): void
    {
        parent::setUp();
        $this->sensor = new XmppSensor();
    }

    public function testSensorAcceptsRequest()
    {
        $data = [
            'text' => 'A message'
        ];

        $body = $this->generateResponseMessage('text', $data);

        $utterance = $this->sensor->interpret(new Request($body));

        $this->assertCount(count($data), $utterance->getData());
        $this->assertEquals($data, $utterance->getData());
    }

    public function testSensorThrowsExceptionOnWrongType()
    {
        $this->expectException(UtteranceUnknownMessageType::class);

        $data = [
            'text' => 'A message'
        ];

        $body = $this->generateResponseMessage('not-allowed', $data);

        $utterance = $this->sensor->interpret(new Request($body));
    }

    public function testUtteranceIsCorrectType()
    {
        $data = [
            'text' => 'A message'
        ];

        $body = $this->generateResponseMessage('text', $data);

        $utterance = $this->sensor->interpret(new Request($body));

        $this->assertInstanceOf(TextUtterance::class, $utterance);
    }

    public function testSensorReturnsAnUtteranceWithCorrectUserData()
    {
        $data = [
            'text' => 'A message'
        ];

        $body = $this->generateResponseMessage('text', $data);
        $utterance = $this->sensor->interpret(new Request($body));
        $user = $utterance->getUser();
        $this->assertEquals($user->getEmail(), $body['from']);
        $this->assertEquals($user->getExternalId(), $body['from']);
    }
}
