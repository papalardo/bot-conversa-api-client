<?php

namespace Papalardo\BotConversaApiClient\Exceptions;

use Exception;

class BotConversaHttpException extends Exception {

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?: "Error with status code: ". $code, $code, $previous);
    }
}
