<?php

namespace OpenDialogAi\Xmpp\Tests;

use Faker\Factory;
use OpenDialogAi\Core\Utterances\Exceptions\FieldNotSupported;
use OpenDialogAi\Core\Utterances\User;
use OpenDialogAi\Xmpp\Helper\UserHelper;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

/**
 * Helper for generating XMPP flavoured utterances with a user to use in tests
 */
class UtteranceGenerator
{
    public static function generateTextUtterance($text = '', $user = null): TextUtterance
    {
        if ($user === null) {
            $user = self::generateUser();
        }

        $utterance = new TextUtterance();
        try {
            $utterance->setText($text);
            $utterance->setUser($user);
            $utterance->setUserId($user->getId());
        } catch (FieldNotSupported $e) {
            //
        }

        return $utterance;
    }

    /**
     * @return User
     */
    public static function generateUser(): User
    {
        $generator = Factory::create();
        $jid = $generator->email;
        $room = 'tanks';
        $userId = UserHelper::createUserId($jid, $room);

        return new User($userId);
    }
}
