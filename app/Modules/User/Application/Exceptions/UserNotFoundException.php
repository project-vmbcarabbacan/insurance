<?php

namespace App\Modules\User\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class UserNotFoundException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('User not found!');
    }

    public function errorCode(): string
    {
        return 'USER_NOT_FOUND';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
