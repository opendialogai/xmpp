<?php

namespace OpenDialogAi\Xmpp\SensorEngine\Sensors;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Utterances\Exceptions\FieldNotSupported;
use OpenDialogAi\Core\Utterances\Exceptions\UtteranceUnknownMessageType;
use OpenDialogAi\Core\Utterances\User;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\SensorEngine\BaseSensor;
use OpenDialogAi\Xmpp\Helper\UserHelper;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class XmppSensor extends BaseSensor
{
    public static $name = 'sensor.core.xmpp';

    /**
     * Interpret a request.
     *
     * @param Request $request
     * @return UtteranceInterface
     * @throws UtteranceUnknownMessageType
     * @throws FieldNotSupported
     */
    public function interpret(Request $request): UtteranceInterface
    {
        Log::debug('Interpreting XMPP request.');

        $content = $request['content'];
        switch ($content['type']) {
            case 'text':
                Log::debug('Received XMPP message.');
                $userId = UserHelper::createUserId($request['from'], $request['room']);
                $utterance = new TextUtterance();
                $utterance->setData($content['data']);
                $utterance->setText($content['data']['text']);
                $utterance->setUserId($userId);

                $utterance->setUser(
                    $this->createUser(
                        $userId,
                        $request->all()
                    )
                );

                return $utterance;
                break;

            default:
                Log::debug("Received unknown XMPP message type {$content['type']}.");
                throw new UtteranceUnknownMessageType('Unknown XMPP Message Type.');
                break;
        }
    }

    /**
     * @param string $userId The webchat id of the user
     * @param array $userData Array of user specific data sent with a request
     * @return User
     */
    private function createUser(string $userId, array $userData): User
    {
        $user = new User($userId);
        isset($userData['from']) ? $user->setExternalId($userData['from']) : null;

        return $user;
    }
}
