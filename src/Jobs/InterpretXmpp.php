<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Utterances\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class InterpretXmpp implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    /**
     * @var array
     */
    public $request;

    /**
     * @var UtteranceInterface
     */
    public $utterance;

    /**
     * Create a new job instance.
     *
     * @param  array  $request
     * @return void
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        Log::debug('Interpreting XMPP request.');
        $content = $this->request['content'];

        Log::debug('Received XMPP message.');
        $this->utterance = $this->buildUtterance($content);
    }

    protected function buildUtterance(array $content): UtteranceInterface
    {
        $utterance = new TextUtterance();
        $utterance->setData($content['data']);
        $utterance->setText($content['data']['text']);
        $utterance->setUserId($this->request['from']);
        if (isset($content['user'])) {
            $utterance->setUser($this->createUser($this->request['from'], $content['user']));
        }

        return $utterance;
    }

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
