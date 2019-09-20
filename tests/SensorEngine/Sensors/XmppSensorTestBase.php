<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests\SensorEngine\Sensors;

use OpenDialogAi\Xmpp\Tests\TestCase;

class XmppSensorTestBase extends TestCase
{
    /**
     * @param $type
     * @param $data
     *
     * @return array
     */
    protected function generateResponseMessage($type, $data): array
    {
        return [
            'notification' => 'message',
            'from' => $from = 'user@email.com',
            'to' => 'person@email.com',
            'lang' => 'en',
            'content' => [
                'type' => $type,
                'author' => $from,
                'data' => $data
            ],
        ];
    }
}
