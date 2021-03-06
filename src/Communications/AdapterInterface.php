<?php

namespace OpenDialogAi\Xmpp\Communications;

use GuzzleHttp\Client;

interface AdapterInterface
{
    public function send();

    public function setClient(Client $client);

    public function getClient(): ?Client;

    public function setUrl(string $url);

    public function getUrl(): ?string;

    public function setPort(int $port);

    public function getPort(): ?int;

    public function setPayload(array $payload);

    public function getPayload(): ?array;

    public function setProtocol(?string $protocol);

    public function getProtocol(): ?string;

    public function setEndpoint(string $endpoint);

    public function getEndpoint(): ?string;

    public function buildUri(): string;
}
