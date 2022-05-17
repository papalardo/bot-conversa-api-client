<?php

namespace Papalardo\BotConversaApiClient;

use Papalardo\BotConversaApiClient\Concerns\BeSingleton;

class BotConversaClientConfig
{
    use BeSingleton;

    protected array $config = [];

    public function config(array $config)
    {
        $this->config = $config;
    }

    public function isThrowable(): bool
    {
        return $this->config['throws'] ?? true;
    }

    public function getAccessToken(): ?string
    {
        return $this->config['accessToken'] ?? null;
    }

}
