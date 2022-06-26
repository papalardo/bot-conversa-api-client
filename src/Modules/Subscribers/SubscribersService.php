<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscribers;

use Papalardo\BotConversaApiClient\Core\BaseService;
use Papalardo\BotConversaApiClient\Core\PaginateObject;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Subscriber;
use Papalardo\BotConversaApiClient\Core\Response;

class SubscribersService extends BaseService
{
    /**
     * @param int $page
     * @return Response<PaginateObject>
     */
    public function paginate(int $page = 1): Response
    {
        return tap($this->httpClient->get('subscribers', compact('page')), function (Response $response) {
            $paginate = PaginateObject::fromArray($response->body());

            $result = SubscribersCollection::fromCallable($paginate->results(), function(array $item) {
                return Subscriber::fromArray($item);
            });

            $paginate->setCollection($result);

            $response->setBody($paginate);
        });
    }
}