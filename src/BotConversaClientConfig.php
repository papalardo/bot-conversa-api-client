<?php

namespace Papalardo\BotConversaApiClient;

use Papalardo\BotConversaApiClient\Concerns\BeSingleton;

class BotConversaClientConfig
{
    use BeSingleton;

    protected array $config = [
        'accessToken' => null,
        'debug' => false,
    ];

    /**
     * @deprecated
     * @param array $config
     * @return $this
     */
    public function config(array $config = []): static
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param string $accessToken
     * @return $this
     */
    public function accessToken(string $accessToken): static
    {
        $this->config['accessToken'] = $accessToken;

        return $this;
    }

    /**
     * @param bool $enableDebug
     * @return $this
     */
    public function debug(bool $enableDebug = true): static
    {
        $this->config['debug'] = $enableDebug;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->config['accessToken'];
    }

    /**
     * @return bool
     */
    public function isDebugging(): bool
    {
        return $this->config['debug'];
    }

}
