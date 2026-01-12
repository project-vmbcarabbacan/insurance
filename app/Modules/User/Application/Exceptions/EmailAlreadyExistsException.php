<?php

namespace App\Modules\User\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class EmailAlreadyExistsException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Email already exists');
    }

    public function errorCode(): string
    {
        return 'EMAIL_FOUND';
    }

    public function statusCode(): int
    {
        return 409;
    }
}
