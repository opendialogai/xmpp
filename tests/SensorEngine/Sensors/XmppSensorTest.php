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

    public function testFormResponse()
    {
        $data = [
            'name' => 'value',
            'text' => 'name: value'
        ];

        $body = $this->generateResponseMessage('text', $data);

        $utterance = $this->sensor->interpret(new Request($body));

        $this->assertCount(count($data), $utterance->getData());
        $this->assertEquals($data, $utterance->getData());
    }
}
