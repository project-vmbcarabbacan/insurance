<?php

namespace App\Modules\Lead\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class LeadMetaUpsertException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Unable to create lead');
    }

    public function errorCode(): string
    {
        return 'UNABLE_TO_CREATE_LEAD';
    }

    public function statusCode(): int
    {
        return 400;
    }
}
