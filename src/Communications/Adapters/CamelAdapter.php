<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Communications\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Xmpp\Communications\AdapterInterface;
use Psr\Http\Message\ResponseInterface;

class CamelAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $endpoint;

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setProtocol(?string $protocol): self
    {
        $this->protocol = is_null($protocol) ? 'https' : $protocol;

        return $this;
    }

    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @return ResponseInterface|null
     */
    public function send(): ?ResponseInterface
    {
        $request = new Request(
            'POST',
            $this->buildUri(),
            $this->payload
        );

        try {
            $response = $this->client->send($request);
            return $response;
        } catch (GuzzleException $e) {
            Log::warning($e->getMessage());
            return null;
        }
    }

    public function buildUri(): string
    {
        return "{$this->getProtocol()}://{$this->getUrl()}::{$this->getPort()}/{$this->getEndpoint()}";
    }
}
