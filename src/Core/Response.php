<?php

namespace Papalardo\BotConversaApiClient\Core;

use Exception;
use Closure;
use Papalardo\BotConversaApiClient\Exceptions\BotConversaHttpException;
use Papalardo\BotConversaApiClient\Exceptions\RecordNotFoundException;
use Papalardo\BotConversaApiClient\Modules\Subscriber\Subscriber;

/** @template T */
class Response
{
    /**
     * @template T
     */
    protected mixed $body = null;

    protected int $statusCode = 0;

    protected ?string $errorMessage = null;

    protected ?Exception $exception = null;

    protected ?Closure $resolvingClosure = null;

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setErrorMessage(?string $errorMessage): static
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setException(Exception $exception): static
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @param T|callable $data
     */
    public function setBody(mixed $data): static
    {
        $this->body = is_callable($data) ? $data($this) : $data;
        return $this;
    }

    public function resolveUsing(?callable $resolvingClosure): static
    {
        $this->resolvingClosure = $resolvingClosure;
        return $this;
    }

    /**
     * @return T|null
     */
    public function body()
    {
        if (is_null($this->resolvingClosure) || is_null($this->body)) {
            return $this->body;
        }

        return call_user_func($this->resolvingClosure, $this->body);
    }

    public function ok(): bool
    {
        return $this->successful();
    }

    public function successful(): bool
    {
        return ! $this->failed();
    }

    public function clientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode <= 499;
    }

    public function serverError(): bool
    {
        return $this->statusCode >= 500 && $this->statusCode <= 599;
    }

    public function failed(): bool
    {
        return $this->clientError() || $this->serverError();
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function throw(): static
    {
        if ($this->failed()) {
            throw $this->getException();
        }

        return $this;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage ?? 'Unknown';
    }

    protected function getException(): Exception
    {
        return match (true) {
            ! is_null($this->exception) => $this->exception,
            $this->statusCode === 404 => new RecordNotFoundException(),
            $this->statusCode >= 400 => new BotConversaHttpException($this->getErrorMessage(), $this->statusCode)
        };
    }
}