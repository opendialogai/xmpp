<?php

namespace OpenDialogAi\Xmpp\Communications;

use OpenDialogAi\Xmpp\Communications\Service\CommunicationService;

interface CommunicationServiceInterface
{
    public function getAdapter(): AdapterInterface;

    public function build(array $data): CommunicationService;

    public function communicate();
}
