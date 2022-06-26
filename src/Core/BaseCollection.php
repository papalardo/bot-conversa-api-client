<?php

namespace Papalardo\BotConversaApiClient\Core;

use Ramsey\Collection\AbstractCollection;

abstract class BaseCollection extends AbstractCollection
{
    public static function fromCallable(array $data, callable $callback): static
    {
        return new static(array_map($callback, $data ?? []));
    }

    public function getType(): string
    {
        return str_replace('Collection', '', get_class($this));
    }
}