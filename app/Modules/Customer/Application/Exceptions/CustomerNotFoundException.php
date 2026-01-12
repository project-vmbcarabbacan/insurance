<?php

namespace App\Modules\Customer\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class CustomerNotFoundException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Customer not found');
    }

    public function errorCode(): string
    {
        return 'CUSTOMER_NOT_FOUND';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
