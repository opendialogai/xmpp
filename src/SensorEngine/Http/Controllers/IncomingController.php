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

class IncomingController extends BaseController
{
    /** @var OpenDialogController */
    private $odController;

    /**
     * @var \OpenDialogAi\SensorEngine\SensorInterface
     */
    private $sensor;

    /**
     * WebchatIncomingController constructor.
     * @param SensorService $sensorService
     * @param OpenDialogController $odController
     */
    public function __construct(SensorService $sensorService, OpenDialogController $odController)
    {
        $this->odController = $odController;
        $this->sensor = $sensorService->getSensor('sensor.core.webchat');
    }
    /**
     * It receives an incoming request
     *
     * @param IncomingMessageInterface $request The request
     *
     * @return Response
     */
    public function receive(IncomingXmppMessage $request): Response
    {
        $messageType = $request->get('notification');

        // Log that the message was successfully received.
        Log::info("XMPP endpoint received a valid message of type ${messageType}.");



        $dataTransferObject = $this->build($request->all());

        // dispatch Job
        InterpretXmpp::dispatch($dataTransferObject);

        return response(null, 200);
    }

    protected function build(array $data): XmppDTO
    {
        $dto = new XmppDTO();
        $dto->setNotification($data['notification'])
            ->setFrom($data['from'])
            ->setTo($data['to'])
            ->setLanguage($data['lang'])
            ->setContentType($data['content']['type'])
            ->setContentData($data['content']['data']['text']);

        return $dto;
    }
}
