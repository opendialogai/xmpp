<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Communications\Service;

use OpenDialogAi\Xmpp\Communications\CommunicationInterface;

class CommunicationService
{
    /**
     * @var CommunicationInterface
     */
    protected $adapter;

    public function __construct(CommunicationInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): CommunicationInterface
    {
        return $this->adapter;
    }

    public function build(array $data): self
    {
        $this->adapter
            ->setUrl($data['url'])
            ->setPort($data['port'])
            ->setProtocol($data['protocol'])
            ->setEndpoint($data['endpoint'])
            ->setPayload($data['payload']);

        return $this;
    }

    public function communicate()
    {
        return $this->adapter->send();
    }
}
