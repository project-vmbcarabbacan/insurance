<?php

namespace App\Modules\Document\Domain\Contracts;

use App\Models\Document;
use App\Modules\Document\Domain\Entities\DocumentEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Eloquent\Collection;

interface DocumentRepositoryContract
{
    public function save(DocumentEntity $documentEntity): void;
    public function getAllDocuments(GenericId $leadId): ?Collection;
    public function getDocument(Uuid $uuid, array $relations = []): ?Document;
    public function deleteDocument(Uuid $uuid): void;
    public function updateType(Uuid $uuid, GenericId $documentTypeId): void;
    public function updateFileName(Uuid $uuid, string $file_path, string $original_name): void;
}
