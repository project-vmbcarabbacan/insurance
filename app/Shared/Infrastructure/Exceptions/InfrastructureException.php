<?php

namespace App\Shared\Infrastructure\Exceptions;

use App\Shared\Domain\Exceptions\ApplicationException;

abstract class InfrastructureException extends ApplicationException
{
    public function statusCode(): int
    {
        return 500;
    }
}
