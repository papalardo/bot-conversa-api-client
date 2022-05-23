<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscriber;

use Papalardo\BotConversaApiClient\Core\BaseService;
use Papalardo\BotConversaApiClient\Exceptions\RecordNotFoundException;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto\SendMessageData;
use Papalardo\BotConversaApiClient\Utils\Str;
use Papalardo\BotConversaApiClient\Exceptions;

class SubscriberService extends BaseService
{
    /**
     * @param Dto\CreateSubscriberData $data
     * @return bool
     * @throws Exceptions\BotConversaHttpException
     * @throws Exceptions\InvalidConfigurationException
     * @throws Exceptions\RecordNotFoundException
     */
    public function create(Dto\CreateSubscriberData $data): bool
    {
        $phone = Str::formatPhone($data->phone);

        $this->httpClient->post('subscriber', array_merge($data->toArray(), [
            'phone' => $phone,
        ]));

        return true;
    }

    /**
     * @param string $phone
     * @param bool $retryRemovingBrNineDigit
     * @return Subscriber
     * @throws Exceptions\BotConversaHttpException
     * @throws Exceptions\InvalidConfigurationException
     * @throws Exceptions\RecordNotFoundException
     */
    public function read(string $phone, bool $retryRemovingBrNineDigit = true): Subscriber
    {
        try {
            $phone = Str::formatPhone($phone);
            $result = $this->httpClient->get('subscriber/'.$phone);
            return Subscriber::fromArray($result);
        } catch (Exceptions\RecordNotFoundException $e) {
            if ($retryRemovingBrNineDigit) {
                return $this->read(Str::removeBrNineDigit($phone), false);
            }
            throw $e;
        }
    }

    /**
     * @param int $subscriberId
     * @param SendMessageData $data
     * @return bool
     * @throws Exceptions\BotConversaHttpException
     * @throws Exceptions\InvalidConfigurationException
     * @throws Exceptions\RecordNotFoundException
     */
    public function sendMessage(int $subscriberId, SendMessageData $data): bool
    {
        $this->httpClient->post("/subscriber/$subscriberId/send_message", $data->toArray());

        return true;
    }
}
