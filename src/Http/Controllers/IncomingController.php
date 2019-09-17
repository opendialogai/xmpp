<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Controllers\OpenDialogController;
use OpenDialogAi\SensorEngine\Contracts\IncomingControllerInterface;
use OpenDialogAi\SensorEngine\Contracts\IncomingMessageInterface;
use OpenDialogAi\SensorEngine\SensorInterface;
use OpenDialogAi\SensorEngine\Service\SensorService;
use OpenDialogAi\Xmpp\Jobs\InterpretXmpp;

class IncomingController extends BaseController implements IncomingControllerInterface
{
    /** @var OpenDialogController */
    private $odController;

    public function __construct(OpenDialogController $odController)
    {
        $this->odController = $odController;
    }

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
        InterpretXmpp::dispatch($request->all());

        return response(null, 200);
    }
}
