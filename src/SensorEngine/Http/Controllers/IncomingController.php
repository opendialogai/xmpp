<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\SensorEngine\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Xmpp\DataTransferObjects\XmppDTO;
use OpenDialogAi\Xmpp\ResponseEngine\Jobs\InterpretXmpp;
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
