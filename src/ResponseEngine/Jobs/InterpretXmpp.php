<?php

namespace OpenDialogAi\Xmpp\ResponseEngine\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OpenDialogAi\Core\Controllers\OpenDialogController;
use OpenDialogAi\Xmpp\Communications\CommunicationServiceInterface;
use OpenDialogAi\Xmpp\ResponseEngine\Message\Xmpp\XmppMessages;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class InterpretXmpp implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    /**
     * @var TextUtterance
     */
    public $utterance;

    /**
     * @var \OpenDialogAi\ResponseEngine\Message\OpenDialogMessages
     */
    public $message;

    /**
     * Create a new job instance.
     *
     * @param  TextUtterance  $utterance
     * @return void
     */
    public function __construct(TextUtterance $utterance)
    {
        $this->utterance = $utterance;
    }

    public function handle(OpenDialogController $odController, CommunicationServiceInterface $communicationService)
    {
        Log::debug('XMPP Job is being handled.');

        /** @var XmppMessages $messageWrapper */
        $messageWrapper = $odController->runConversation($this->utterance);

        $messageToPost = $messageWrapper->getMessageToPost();
        Log::debug(sprintf('Sending response: %s', json_encode($messageToPost)));

        $communicationService->getAdapter()->setPayload($messageToPost);
        $response = $communicationService->communicate();

        if (!is_null($response)) {
            Log::debug('Response sent successfully.');
        }
    }
}
