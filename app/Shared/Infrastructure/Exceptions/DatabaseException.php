<?php

namespace App\Shared\Infrastructure\Exceptions;

final class DatabaseException extends InfrastructureException
{
    public function errorCode(): string
    {
        return 'DATABASE_ERROR';
    }
}
