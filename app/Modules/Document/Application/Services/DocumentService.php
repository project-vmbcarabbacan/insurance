<?php

namespace App\Modules\Document\Application\Services;

use App\Models\Lead;
use App\Modules\Document\Domain\Contracts\DocumentRepositoryContract;
use App\Modules\Document\Domain\Entities\DocumentEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DocumentService
{
    public function __construct(
        protected DocumentRepositoryContract $document_repository_contract
    ) {}

    public function store(UploadedFile $file, Lead $lead): DocumentEntity
    {
        $leadId = GenericId::fromId($lead->id);
        $uuid = (string) Str::uuid();
        $fileName = $uuid . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs("documents/{$leadId->value()}", $fileName, 'public');

        $document = new DocumentEntity(
            lead_id: $leadId,
            originalName: $file->getClientOriginalName(),
            path: $path,
            mimeType: $file->getMimeType(),
            size: $file->getSize(),
        );

        $lead->documents()->create($document->toArray());

        return $document;
    }
}
