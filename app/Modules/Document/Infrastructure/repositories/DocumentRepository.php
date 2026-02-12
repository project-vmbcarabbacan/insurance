<?php

namespace App\Modules\Document\Infrastructure\repositories;

use App\Models\Document;
use App\Modules\Document\Domain\Contracts\DocumentRepositoryContract;
use App\Modules\Document\Domain\Entities\DocumentEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository implements DocumentRepositoryContract
{
    public function save(DocumentEntity $documentEntity): void
    {
        Document::create($documentEntity->toArray());
    }

    public function getAllDocuments(GenericId $leadId): ?Collection
    {
        return Document::lead($leadId)->uploaded()->get();
    }

    public function getDocument(Uuid $uuid, array $relations = []): ?Document
    {
        return Document::with($relations)
            ->uuid($uuid)
            ->first();
    }

    public function deleteDocument(Uuid $uuid): void
    {
        Document::uuid($uuid)->delete();
    }

    public function updateType(Uuid $uuid, GenericId $documentTypeId): void
    {
        Document::uuid($uuid)->update([
            'document_type_id' => $documentTypeId->value()
        ]);
    }

    public function updateFileName(Uuid $uuid, string $file_path, string $original_name): void
    {
        Document::uuid($uuid)->update([
            'file_path' => $file_path,
            'original_name' => $original_name
        ]);
    }
}
