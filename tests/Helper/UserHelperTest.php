<?php

namespace OpenDialogAi\Xmpp\Tests\Helper;

use OpenDialogAi\Xmpp\Helper\UserHelper;
use OpenDialogAi\Xmpp\Tests\TestCase;

class UserHelperTest extends TestCase
{
    public function testCreateNewUserId()
    {
        $testUserId = 'agent1';
        $testRoom = 'tanks';

        $this->assertEquals($testUserId.UserHelper::SEPARATOR.$testRoom, UserHelper::createUserId($testUserId, $testRoom));
        $this->assertEquals($testUserId, UserHelper::createUserId($testUserId));
    }

    public function testGetUserId()
    {
        $testUserId = 'agent2'.UserHelper::SEPARATOR.'political';
        $testUserIdArray = explode(UserHelper::SEPARATOR, $testUserId);
        $testUserNoRoom = 'agent2';

        $this->assertEquals($testUserNoRoom, UserHelper::getUserId($testUserNoRoom)[UserHelper::ID_USER]);
        $this->assertEquals($testUserIdArray[0], UserHelper::getUserId($testUserId)[UserHelper::ID_USER]);
        $this->assertEquals($testUserIdArray[1], UserHelper::getUserId($testUserId)[UserHelper::ROOM]);
    }

    public function testGetUserIdWrong()
    {
        $testUserNoRoom = 'agent3';

        $this->assertArrayNotHasKey(UserHelper::ROOM, UserHelper::getUserId($testUserNoRoom));
    }
}
