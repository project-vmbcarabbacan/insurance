<?php

namespace App\Shared\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class AuditException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Something went wrong while fetching audits');
    }

    public function errorCode(): string
    {
        return 'AUDIT_ERROR';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
