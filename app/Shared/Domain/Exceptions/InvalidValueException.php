<?php

namespace App\Shared\Domain\Exceptions;

use DomainException;

final class InvalidValueException extends DomainException
{
    public static function forValue(
        string $value,
        string $reason
    ): self {
        return new self(
            sprintf("Invalid value '%s': %s", $value, $reason)
        );
    }

    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
