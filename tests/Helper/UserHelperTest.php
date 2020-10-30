<?php

namespace OpenDialogAi\Xmpp\Tests\Helper;

use OpenDialogAi\Xmpp\Helper\UserHelper;
use OpenDialogAi\Xmpp\Tests\TestCase;

class UserHelperTest extends TestCase
{
    const SEPARATOR = '::';

    public function testCreateNewUserId()
    {
        $testUserId = 'agent1';
        $testRoom = 'tanks';

        $this->assertEquals($testUserId.self::SEPARATOR.$testRoom, UserHelper::createUserId($testUserId, $testRoom));
        $this->assertEquals($testUserId, UserHelper::createUserId($testUserId));
    }

    public function testGetUserId()
    {
        $testUserId = 'agent2'.self::SEPARATOR.'political';
        $testUserIdArray = explode(self::SEPARATOR, $testUserId);
        $testUserNoRoom = 'agent2';

        $this->assertEquals($testUserNoRoom, UserHelper::getUserId($testUserNoRoom)['id']);
        $this->assertEquals($testUserIdArray[0], UserHelper::getUserId($testUserId)['id']);
        $this->assertEquals($testUserIdArray[1], UserHelper::getUserId($testUserId)['room']);
    }

    public function testGetUserIdWrong()
    {
        $testUserNoRoom = 'agent3';

        $this->assertArrayNotHasKey('room', UserHelper::getUserId($testUserNoRoom));
    }
}
