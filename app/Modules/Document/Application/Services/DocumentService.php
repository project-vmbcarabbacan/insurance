<?php

namespace App\Modules\Document\Application\Services;

use App\Modules\Document\Application\Exceptions\DocumentNotFoundException;
use App\Modules\Document\Domain\Contracts\DocumentRepositoryContract;
use App\Modules\Document\Domain\Entities\DocumentEntity;
use App\Modules\Lead\Application\Exceptions\LeadUuidNotFoundException;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function __construct(
        protected DocumentRepositoryContract $documentRepositoryContract,
        protected LeadService $leadService,
        protected DocumentTypeService $documentTypeService,
    ) {}

    public function store(UploadedFile $file, Uuid $leadUuid): DocumentEntity
    {
        $lead = $this->getLeadByUuid($leadUuid);

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

        insurance_audit(
            $lead,
            AuditAction::DOCUMENT_UPLOADED,
            null,
            $document->toArray()
        );

        return $document;
    }

    public function allDocuments(Uuid $lead_uuid)
    {
        $lead = $this->getLeadByUuid($lead_uuid);

        $leadId = GenericId::fromId($lead->id);
        $product = LeadProductType::fromValue($lead->insurance_product_code);
        return [
            $this->documentRepositoryContract->getAllDocuments($leadId),
            $this->documentTypeService->getDocumentWithGeneral($product)
        ];
    }

    public function updateType(Uuid $leadUuid, Uuid $documentUuid, GenericId $documentTypeId)
    {
        $document = $this->getDocument($documentUuid);

        $lead = $this->getLeadByUuid($leadUuid);

        /* update databsae column type */
        $this->documentRepositoryContract->updateType($documentUuid, $documentTypeId);

        insurance_audit(
            $lead,
            AuditAction::DOCUMENT_UPDATED,
            ['document_type' => $document->document_type_id],
            ['document_type' => $documentTypeId->value()]
        );
    }

    public function deleteDocument(Uuid $documentUuid)
    {
        $document = $this->getDocument($documentUuid, ['documentType']);

        $lead = $this->getLeadById(GenericId::fromId($document->lead_id));

        /* delete file */
        Storage::disk('public')->delete($document->file_path);

        /* delte database record */
        $this->documentRepositoryContract->deleteDocument($documentUuid);

        insurance_audit(
            $lead,
            AuditAction::DOCUMENT_DELETED,
            null,
            [
                'file_name' => $document->original_name,
                'type' => $document->documentType?->name ?? 'Not Assigned'
            ]
        );
    }

    public function getDocument(Uuid $uuid, ?array $relation = [])
    {
        $document = $this->documentRepositoryContract->getDocument($uuid, $relation);
        if (!$document) throw new DocumentNotFoundException();

        return $document;
    }

    public function renameDocumentFileName(Uuid $documentUuid, string $newFileName)
    {
        $document = $this->getDocument($documentUuid);

        $lead = $this->getLeadById(GenericId::fromId($document->lead_id));

        $disk = Storage::disk('public');

        if (!$disk->exists($document->file_path)) {
            throw new \RuntimeException('Physical file not found on server.');
        }

        /* get directory */
        $directory = dirname($document->file_path);
        /* get orignal extension */
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        /* sanitize filename */
        $safeName = Str::slug(pathinfo($newFileName, PATHINFO_FILENAME));

        $newPath = $directory . '/' . $safeName . '.' . $extension;

        // Prevent overwriting an existing file
        if ($disk->exists($newPath)) {
            throw new \RuntimeException('File with this name already exists.');
        }

        // rename the file
        $disk->move($document->file_path, $newPath);

        /* update the filename and original name */
        $this->documentRepositoryContract->updateFileName(
            $documentUuid,
            $newPath,
            $safeName . '.' . $extension
        );

        insurance_audit(
            $lead,
            AuditAction::DOCUMENT_RENAME,
            [
                'file_path' => $document->file_path,
                'original_name' => $document->original_name
            ],
            [
                'file_path' => $newPath,
                'original_name' => $safeName . '.' . $extension
            ]
        );
    }

    private function getLeadByUuid(Uuid $uuid)
    {
        $lead = $this->leadService->getLeadByUuid($uuid);
        if (!$lead) throw new LeadUuidNotFoundException();

        return $lead;
    }

    private function getLeadById(GenericId $leadId)
    {
        $lead = $this->leadService->getLeadByIdd($leadId);
        if (!$lead) throw new LeadUuidNotFoundException();

        return $lead;
    }
}
