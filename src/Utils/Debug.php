<?php

namespace Papalardo\BotConversaApiClient\Utils;

use Papalardo\BotConversaApiClient\BotConversaClientConfig;

class Debug
{
    public static function varDump(mixed $value, mixed ...$values)
    {
        if (BotConversaClientConfig::i()->isDebugging()) {
            var_dump($value, $values);
        }
    }
}
