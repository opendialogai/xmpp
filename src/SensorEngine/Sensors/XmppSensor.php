<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\SensorEngine\Sensors;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Utterances\Exceptions\FieldNotSupported;
use OpenDialogAi\Core\Utterances\Exceptions\UtteranceUnknownMessageType;
use OpenDialogAi\Core\Utterances\User;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\SensorEngine\BaseSensor;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class XmppSensor extends BaseSensor
{
    protected static $name = 'sensor.core.xmpp';

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
                Log::debug('Received webchat message.');
                $utterance = new TextUtterance();
                $utterance->setData($content['data']);
                $utterance->setText($content['data']['text']);
                $utterance->setUserId($request['user_id']);
                if (isset($content['user'])) {
                    $utterance->setUser($this->createUser($request['user_id'], $content['user']));
                }
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

        isset($userData['first_name']) ? $user->setFirstName($userData['first_name']) : null;
        isset($userData['last_name']) ? $user->setLastName($userData['last_name']) : null;
        isset($userData['email']) ? $user->setEmail($userData['email']) : null;
        isset($userData['external_id']) ? $user->setExternalId($userData['external_id']) : null;
        isset($userData['ipAddress']) ? $user->setIPAddress($userData['ipAddress']) : null;
        isset($userData['country']) ? $user->setCountry($userData['country']) : null;
        isset($userData['browserLanguage']) ? $user->setBrowserLanguage($userData['browserLanguage']) : null;
        isset($userData['os']) ? $user->setOS($userData['os']) : null;
        isset($userData['browser']) ? $user->setBrowser($userData['browser']) : null;
        isset($userData['timezone']) ? $user->setTimezone($userData['timezone']) : null;
        isset($userData['custom']) ? $user->setCustomParameters($userData['custom']) : null;

        return $user;
    }
}
