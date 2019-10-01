<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Communications\Service;

use OpenDialogAi\Xmpp\Communications\AdapterInterface;
use OpenDialogAi\Xmpp\Communications\CommunicationServiceInterface;
use Psr\Http\Message\ResponseInterface;

class CommunicationService implements CommunicationServiceInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    public function build(array $data): self
    {
        $this->adapter
            ->setUrl($data['url'])
            ->setPort($data['port'])
            ->setProtocol($data['protocol'])
            ->setEndpoint($data['endpoint']);

        return $this;
    }

    /**
     * @return ResponseInterface|null
     */
    public function communicate(): ?ResponseInterface
    {
        return $this->adapter->send();
    }
}
