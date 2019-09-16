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
        $arr = [
            'notification' => 'message',
            'user_id' => 'someuser',
            'author' => '{jid}',
            'content' => [
                'author' => 'me',
                'type' => $type,
                'data' => $data,
                'user' => [
                    'ipAddress' => '127.0.0.1',
                    'country' => 'UK',
                    'browserLanguage' => 'en-gb',
                    'os' => 'macos',
                    'browser' => 'safari',
                    'timezone' => 'GMT',
                ],
            ],
        ];

        return $arr;
    }
}
