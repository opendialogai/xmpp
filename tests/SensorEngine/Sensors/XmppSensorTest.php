<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\SensorEngine\Sensors;

use Illuminate\Http\Request;
use OpenDialogAi\Xmpp\SensorEngine\Sensors\XmppSensor;

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
