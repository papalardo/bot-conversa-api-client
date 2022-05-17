<?php

namespace Papalardo\BotConversaApiClient\Contracts;

interface IHttpClient
{
    public function get(string $path);

    public function post(string $path, array $payload);
}
