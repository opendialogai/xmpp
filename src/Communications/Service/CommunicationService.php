<?php

namespace OpenDialogAi\Xmpp\Communications\Service;

use GuzzleHttp\Client;
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

    public function build(array $data, Client $client = null): self
    {
        if (is_null($client)) {
            $client = new Client();
        }

        $this->adapter
            ->setUrl($data['url'])
            ->setPort($data['port'])
            ->setProtocol($data['protocol'])
            ->setEndpoint($data['endpoint'])
            ->setClient($client);

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
