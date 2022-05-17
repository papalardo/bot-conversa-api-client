<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscriber;

use Papalardo\BotConversaApiClient\Core\BaseService;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto\SendMessageData;
use Papalardo\BotConversaApiClient\Utils\Str;

class SubscriberService extends BaseService
{
    public function create(Dto\CreateSubscriberData $data): bool
    {
        $this->httpClient->post('subscriber', array_merge($data->toArray(), [
            'phone' => Str::formatPhone($data->phone)
        ]));

        return true;
    }

    public function read(string $phone): Subscriber
    {
        $result = $this->httpClient->get('subscriber/'.Str::formatPhone($phone));

        return Subscriber::fromArray($result);
    }

    public function sendMessage(int $subscriberId, SendMessageData $data): bool
    {
        $this->httpClient->post("/subscriber/$subscriberId/send_message", $data->toArray());

        return true;
    }
}
