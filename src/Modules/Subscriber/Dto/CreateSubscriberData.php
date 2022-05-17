<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscriber\Dto;

use Papalardo\BotConversaApiClient\Core\DtoObject;

class CreateSubscriberData extends DtoObject
{
    public string $phone;

    public string $firstName;

    public string $lastName;
}
