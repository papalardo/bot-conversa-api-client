<?php

namespace Papalardo\BotConversaApiClient;

use Papalardo\BotConversaApiClient\Modules\Subscriber\SubscriberService;

class BotConversaClient
{
    protected HttpClient $client;

    protected string $baseUrl = 'https://backend.botconversa.com.br/api/v1/webhook';

    public function __construct(string $accessToken = null)
    {
        $this->client = new HttpClient($this->baseUrl, $accessToken ?? BotConversaClientConfig::i()->getAccessToken());
    }

    public static function make(string $accessToken = null): static
    {
        return new static($accessToken);
    }

    public function subscriber(): SubscriberService
    {
        return new SubscriberService($this->client);
    }
}
