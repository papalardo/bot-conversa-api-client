<?php

namespace Papalardo\BotConversaApiClient\Modules\Subscriber;

use Papalardo\BotConversaApiClient\Core\DtoObject;

class Subscriber extends DtoObject
{
    protected int $id;

    protected string $fullName;

    protected string $firstName;

    protected string $lastName;

    protected string $phone;

    protected string $ddd;

    public function id(): int
    {
        return $this->id;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function phone($onlyDigits = true): string
    {
        if ($onlyDigits) {
            return preg_replace('/\D/', '', $this->phone);
        }

        return $this->phone;
    }

    public function ddd(): string
    {
        return $this->ddd;
    }
}
