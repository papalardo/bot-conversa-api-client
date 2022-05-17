<?php

namespace Papalardo\BotConversaApiClient\Utils;

class Str
{
    public static function toSnake($s): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $s)), '_');
    }

    public static function digits($s): string
    {
        return preg_replace('/\D/', '', $s);
    }

    public static function formatPhone($f): string
    {
        return '+' . static::digits($f);
    }

    public static function toCamel($s): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $s))));
    }
}
