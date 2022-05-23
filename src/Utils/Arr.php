<?php

namespace Papalardo\BotConversaApiClient\Utils;

class Arr
{
    public static function transformKeys($values, $callback)
    {
        $keys = array_map($callback, array_keys($values));

        return array_combine($keys, $values);
    }
}
