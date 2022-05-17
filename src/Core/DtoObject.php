<?php

namespace Papalardo\BotConversaApiClient\Core;

use Papalardo\BotConversaApiClient\Utils\Str;

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

    public function toArray($snakable = true): array
    {
        $values = get_object_vars($this);

        if ($snakable) {
            return $this->arrayToSnakeCase($values);
        }

        return $values;
    }

    private function arrayToSnakeCase(array $values): array
    {
        $keys = array_map(function($key) {
            return Str::toSnake($key);
        }, array_keys($values));

        return array_combine($keys, $values);
    }
}
