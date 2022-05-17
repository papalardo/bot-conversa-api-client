<?php

namespace Papalardo\BotConversaApiClient\Core;

use Papalardo\BotConversaApiClient\HttpClient;

class BaseService
{
    protected HttpClient $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
