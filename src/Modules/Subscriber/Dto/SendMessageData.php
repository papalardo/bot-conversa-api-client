<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscriber\Dto;

use Papalardo\BotConversaApiClient\Core\DtoObject;

class SendMessageData extends DtoObject
{
    /**
     * @var string text|file
     */
    public string $type = 'text';

    public string $value;
}
