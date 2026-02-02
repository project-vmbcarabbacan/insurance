<?php

namespace App\Modules\Master\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class InsuranceProductException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Insurance product is empty');
    }

    public function errorCode(): string
    {
        return 'PRODUCT_EMPTY';
    }

    public function statusCode(): int
    {
        return 400;
    }
}
