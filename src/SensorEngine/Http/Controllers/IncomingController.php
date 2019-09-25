<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\SensorEngine\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Controllers\OpenDialogController;
use OpenDialogAi\SensorEngine\Service\SensorService;
use OpenDialogAi\Xmpp\DataTransferObjects\XmppDTO;
use OpenDialogAi\Xmpp\ResponseEngine\Jobs\InterpretXmpp;
use Illuminate\Routing\Controller as BaseController;
use OpenDialogAi\SensorEngine\Contracts\IncomingControllerInterface;
use OpenDialogAi\SensorEngine\Contracts\IncomingMessageInterface;
use OpenDialogAi\Xmpp\SensorEngine\Http\Requests\IncomingXmppMessage;
use OpenDialogAi\Xmpp\SensorEngine\Sensors\XmppSensor;

class IncomingController extends BaseController
{
    /** @var OpenDialogController */
    private $odController;

    /**
     * @var \OpenDialogAi\SensorEngine\SensorInterface
     */
    private $sensor;

    /**
     * IncomingController constructor.
     * @param SensorService $sensorService
     * @param OpenDialogController $odController
     */
    public function __construct(SensorService $sensorService, OpenDialogController $odController)
    {
        $this->odController = $odController;
        $this->sensor = $sensorService->getSensor('sensor.core.xmpp');
    }
    /**
     * It receives an incoming request
     *
     * @param IncomingXmppMessage $request The request
     *
     * @return Response
     */
    public function receive(IncomingXmppMessage $request): Response
    {
        $messageType = $request->get('notification');

        // Log that the message was successfully received.
        Log::info("XMPP endpoint received a valid message of type ${messageType}.");

        $utterance = $this->sensor->interpret($request);

        // dispatch Job
        InterpretXmpp::dispatch($utterance);

        return response(null, 200);
    }
}
