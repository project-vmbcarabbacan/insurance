<?php

namespace App\Modules\Document\Infrastructure\repositories;

use App\Models\Document;
use App\Modules\Document\Domain\Contracts\DocumentRepositoryContract;
use App\Modules\Document\Domain\Entities\DocumentEntity;

class DocumentRepository implements DocumentRepositoryContract
{
    public function save(DocumentEntity $documentEntity): void
    {
        Document::create($documentEntity->toArray());
    }
}
