<?php

namespace Papalardo\BotConversaApiClient\Concerns;

trait BeSingleton
{
    /** @var $this */
    private static $instance;

    public static function i(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}
