<?php

namespace Papalardo\BotConversaApiClient\Core;

use Ramsey\Collection\AbstractCollection;

/**
 * @template T extends AbstractCollection
 */
class PaginateObject extends DtoObject
{
    protected array $results = [];

    /**
     * @var T|null
     */
    protected ?AbstractCollection $collection = null;

    protected int $count = 0;

    protected ?string $previous = null;

    protected ?string $next = null;

    /**
     * @return array
     */
    public function results(): array
    {
        return $this->results;
    }

    /**
     * @param T $collection
     * @return $this
     */
    public function setCollection(AbstractCollection $collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return T|null
     */
    public function collection(): ?AbstractCollection
    {
        return $this->collection;
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return ! is_null($this->next);
    }

    /**
     * @return bool
     */
    public function hasPrevious(): bool
    {
        return ! is_null($this->previous);
    }
}