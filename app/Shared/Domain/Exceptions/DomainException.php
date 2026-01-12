<?php

namespace App\Shared\Domain\Exceptions;

abstract class DomainException extends ApplicationException
{
    public function statusCode(): int
    {
        return 422; // Unprocessable Entity
    }
}
