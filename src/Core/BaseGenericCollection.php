<?php

namespace Papalardo\BotConversaApiClient\Core;

use Ramsey\Collection\Collection;

class BaseGenericCollection extends Collection
{
    public static function fromArray(array $data): static
    {
        $static = new static('array');
        foreach ($data as $item) {
            $static->add($item);
        }
        return $static;
    }
}