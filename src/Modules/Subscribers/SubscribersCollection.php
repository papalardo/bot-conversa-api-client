<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscribers;

use Papalardo\BotConversaApiClient\Core\BaseCollection;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Subscriber;

class SubscribersCollection extends BaseCollection
{
    public function getType(): string
    {
        return Subscriber::class;
    }
}