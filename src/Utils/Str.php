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

    public static function isBrPhone($p): bool
    {
        return str_starts_with(static::formatPhone($p), '+55');
    }

    public static function hasBrNineDigit($p): bool
    {
        return strlen(static::formatPhone($p)) === 14 && strpos(static::formatPhone($p), '9');
    }

    public static function removeBrNineDigit($p): string
    {
        $phone = static::formatPhone($p);
        if (static::isBrPhone($phone) && static::hasBrNineDigit($phone)) {
            return substr($phone, 0, 5).substr($phone, 6);
        }
        return $phone;
    }
}
