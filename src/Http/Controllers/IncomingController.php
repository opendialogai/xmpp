<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        return response(null, 200);
    }

    public function test(Request $request): Response
    {
        return response(null, 200);
    }
}
