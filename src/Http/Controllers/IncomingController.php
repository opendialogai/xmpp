<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Xmpp\Jobs\InterpretXmpp;
use Illuminate\Routing\Controller as BaseController;
use OpenDialogAi\SensorEngine\Contracts\IncomingControllerInterface;
use OpenDialogAi\SensorEngine\Contracts\IncomingMessageInterface;

class IncomingController extends BaseController implements IncomingControllerInterface
{
    /**
     * It receives an incoming request
     *
     * @param IncomingMessageInterface $request The request
     *
     * @return Response
     */
    public function receive(IncomingMessageInterface $request): Response
    {
        $messageType = $request->get('notification');

        // Log that the message was successfully received.
        Log::info("XMPP endpoint received a valid message of type ${messageType}.");

        // dispatch Job
        InterpretXmpp::dispatch();

        return response(null, 200);
    }
}
