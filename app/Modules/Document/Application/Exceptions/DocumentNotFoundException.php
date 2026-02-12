<?php

namespace App\Modules\Document\Application\Exceptions;

use App\Shared\Application\Exceptions\ApplicationLayerException;

final class DocumentNotFoundException extends ApplicationLayerException
{
    public function __construct()
    {
        return parent::__construct('Document not found');
    }

    public function errorCode(): string
    {
        return 'DOCUMENT_NOT_FOUND';
    }

    public function statusCode(): int
    {
        return 404;
    }
}
