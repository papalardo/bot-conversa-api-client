<?php

namespace Papalardo\BotConversaApiClient\Core;

use Papalardo\BotConversaApiClient\Utils\Arr;
use Papalardo\BotConversaApiClient\Utils\Str;
use stdClass;

class DtoObject
{
    public function __construct(array $payload)
    {
        $this->fill($payload);
    }

    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    protected function fill(array $data): void
    {
        foreach($data as $property => $value) {
            $propertyCamelCase = Str::toCamel($property);
            if(property_exists($this, $property) || property_exists($this, $propertyCamelCase)) {
                $this->{$propertyCamelCase} = $value;
            }
        }
    }

    /**
     * @param bool $snakable
     * @return array
     */
    public function toArray(bool $snakable = true): array
    {
        $values = get_object_vars($this);

        if ($snakable) {
            return $this->arrayToSnakeCase($values);
        }

        return $values;
    }

    /**
     * @param bool $snakable
     * @return stdClass
     */
    public function toObject(bool $snakable = true): stdClass
    {
        return (object) $this->toArray($snakable);
    }

    private function arrayToSnakeCase(array $values): array
    {
        return Arr::transformKeys($values, function($key) {
            return Str::toSnake($key);
        });
    }

    public function __debugInfo()
    {
        return Arr::transformKeys($this->toArray(false), function($key) {
            return "#$key";
        });
    }
}
