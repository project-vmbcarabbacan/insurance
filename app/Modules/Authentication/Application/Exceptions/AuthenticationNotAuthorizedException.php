<?php

namespace App\Modules\Authentication\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class AuthenticationNotAuthorizedException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('User role not allowed');
    }

    public function errorCode(): string
    {
        return 'ROLE_NOT_AUTHORIZED';
    }

    public function statusCode(): int
    {
        return 403;
    }
}
