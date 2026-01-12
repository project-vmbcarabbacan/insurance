<?php

namespace App\Modules\Lead\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class LeadUuidNotFoundException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Lead uuid not found');
    }

    public function errorCode(): string
    {
        return 'LEAD_UUID_NOT_FOUND';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
