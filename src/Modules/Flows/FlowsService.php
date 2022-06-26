<?php

namespace Papalardo\BotConversaApiClient\Modules\Flows;

use Papalardo\BotConversaApiClient\Core\BaseService;
use Papalardo\BotConversaApiClient\Core\Response;
use Papalardo\BotConversaApiClient\Core\BaseGenericCollection;
use Papalardo\BotConversaApiClient\Exceptions\RecordNotFoundException;

class FlowsService extends BaseService
{
    /**
     * @return Response<array>
     */
    protected function getAll(): Response
    {
        return $this->httpClient->get('/flows');
    }

    /**
     * @template T
     * @return Response<Flow[]>
     */
    public function all(): Response
    {
        return tap($this->getAll(), function (Response $response) {
            $result = FlowCollection::fromCallable($response->body() ?? [], function(array $flowItem) {
                return Flow::fromArray($flowItem);
            });

            $response->setBody($result);
        });
    }

    /**
     * @param string|int $value
     * @param string $key
     * @return Response<Flow|null>
     */
    public function read(string|int $value, string $key = 'id'): Response
    {
        $response = $this->getAll();

        $result = BaseGenericCollection::fromArray($response->body() ?? [])
            ->filter(function($item) use ($key, $value) {
                return $item[$key] === $value;
            });

        if (count($result) > 0) {
            return $response->setBody(
                Flow::fromArray($result->first())
            );
        }

        return $response
            ->setException(new RecordNotFoundException)
            ->setBody(null);
    }
}