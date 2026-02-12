<?php

namespace App\Modules\Document\Domain\Contracts;

use App\Modules\Document\Domain\Entities\DocumentEntity;

interface DocumentRepositoryContract
{
    public function save(DocumentEntity $documentEntity): void;
}
