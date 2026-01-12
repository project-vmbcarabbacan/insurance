<?php

namespace App\Modules\Authentication\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class AuthenticationFailedException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('User not found!');
    }

    public function errorCode(): string
    {
        return 'INVALID_CREDENTIALS';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
