<?php

namespace App\Modules\Customer\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class PhoneNumberExistsException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Phone number found');
    }

    public function errorCode(): string
    {
        return 'PHONE_NUMBER_FOUND';
    }

    public function statusCode(): int
    {
        return 409;
    }
}
