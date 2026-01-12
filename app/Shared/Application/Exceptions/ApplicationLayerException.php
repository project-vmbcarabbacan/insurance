<?php

namespace App\Shared\Application\Exceptions;

use App\Shared\Domain\Exceptions\ApplicationException;

abstract class ApplicationLayerException extends ApplicationException
{
    public function statusCode(): int
    {
        return 400;
    }
}
