<?php

namespace App\Modules\Authentication\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class AuthenticationStatusNotActiveException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('User status not active');
    }

    public function errorCode(): string
    {
        return 'STATUS_NOT_ACTIVE';
    }

    public function statusCode(): int
    {
        return 403;
    }
}
