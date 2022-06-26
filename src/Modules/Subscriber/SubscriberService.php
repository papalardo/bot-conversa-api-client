<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscriber;

use Papalardo\BotConversaApiClient\Core\BaseService;
use Papalardo\BotConversaApiClient\Core\Response;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto\SendFlowData;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Dto\SendMessageData;
use Papalardo\BotConversaApiClient\Utils\Str;

class SubscriberService extends BaseService
{
    /**
     * @param Dto\CreateSubscriberData $data
     * @return Response<bool>
     */
    public function create(Dto\CreateSubscriberData $data): Response
    {
        $phone = Str::formatPhone($data->phone);

        $response = $this->httpClient
            ->post('subscriber', array_merge($data->toArray(), [
                'phone' => $phone,
            ]));

        return $response->setBody($response->ok());
    }

    /**
     * @param string $phone
     * @param bool $retryRemovingBrNineDigit
     * @return Response<Subscriber>
     */
    public function read(string $phone, bool $retryRemovingBrNineDigit = true): Response
    {
        $phone = Str::formatPhone($phone);

        $response = $this->httpClient->get('subscriber/'.$phone);

        if ($response->failed()) {
            return $retryRemovingBrNineDigit
                ? $this->read(Str::removeBrNineDigit($phone), false)
                : $response;
        }

        return $response->resolveUsing(fn (array $result) => Subscriber::fromArray($result));
    }

    /**
     * @param string $phone
     * @return Response<int>
     */
    public function getSubscriberId(string $phone): Response
    {
        return tap($this->read($phone), function (Response $response) {
            $subscriber = $response->body();
            $response->setBody($subscriber instanceof Subscriber ? $subscriber->id() : null)
                ->resolveUsing(null);
        });
    }

    /**
     * @param int|string $subscriberPhoneOrId
     * @param callable $callback
     * @param mixed|null $bodyOnError
     * @return Response
     */
    protected function proxyUsingIdOrPhone(int|string $subscriberPhoneOrId, callable $callback, mixed $bodyOnError = null): Response
    {
        if (is_int($subscriberPhoneOrId)) {
            return $callback($subscriberPhoneOrId);
        }

        $subscriberIdResponse = $this->getSubscriberId($subscriberPhoneOrId);

        if ($subscriberIdResponse->failed() || is_null($subscriberIdResponse->body())) {
            return $subscriberIdResponse->setBody($bodyOnError);
        }

        return $callback($subscriberIdResponse->body());
    }

    /**
     * @param int|string $subscriberPhoneOrId
     * @param SendMessageData $data
     * @return Response<bool>
     */
    public function sendMessage(int|string $subscriberPhoneOrId, SendMessageData $data): Response
    {
        return $this->proxyUsingIdOrPhone($subscriberPhoneOrId, function(int $subscriberId) use ($data) {
            $response = $this->httpClient
                ->post("/subscriber/$subscriberId/send_message", $data->toArray())
                ->setBody(fn ($response) => $response->ok());
        }, false);
    }

    /**
     * @param int|string $subscriberPhoneOrId
     * @param SendFlowData $data
     * @return Response<bool>
     */
    public function sendFlow(int|string $subscriberPhoneOrId, SendFlowData $data): Response
    {
        return $this->proxyUsingIdOrPhone($subscriberPhoneOrId, function(int $subscriberId) use ($data) {
            return $this->httpClient
                ->post("/subscriber/$subscriberId/send_flow", $data->toArray())
                ->setBody(fn ($response) => $response->ok());
        }, false);
    }
}
