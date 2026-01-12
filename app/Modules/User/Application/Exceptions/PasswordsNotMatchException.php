<?php

namespace App\Modules\User\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class PasswordsNotMatchException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Password and confirm password do not match');
    }

    public function errorCode(): string
    {
        return 'CONFIRM_PASSWORD_DO_NO_MATCH';
    }

    public function statusCode(): int
    {
        return 400;
    }
}
