<?php

namespace Papalardo\BotConversaApiClient\Modules\Flows;

use Papalardo\BotConversaApiClient\Core\DtoObject;

/**
 * @template DtoResponse
 */
class Flow extends DtoObject
{
    protected int $id;

    protected string $name;

    public function id(): int
    {
        return $this->id;
    }

    public function name(): int
    {
        return $this->name;
    }
}