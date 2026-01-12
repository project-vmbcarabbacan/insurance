<?php

namespace App\Shared\Domain\Exceptions;

use RuntimeException;

abstract class ApplicationException extends RuntimeException
{
    abstract public function errorCode(): string;

    abstract public function statusCode(): int;

    public function toArray(): array
    {
        return [
            'error' => $this->errorCode(),
            'message' => $this->getMessage()
        ];
    }
}
