<?php

namespace App\Modules\Document\Infrastructure\Http\Controllers;

use App\Modules\Document\Application\Services\DocumentService;
use App\Modules\Document\Infrastructure\Http\Requests\DocumentStoreRequest;
use App\Modules\Document\Infrastructure\Http\Requests\DocumentUpdateType;
use App\Modules\Document\Infrastructure\Http\Requests\UuidDocumentRequest;
use App\Modules\Document\Infrastructure\Http\Resources\DocumentResource;
use App\Modules\Lead\Infrastructure\Http\Requests\UuidLeadRequest;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DocumentController
{
    public function __construct(
        protected DocumentService $documentService
    ) {}

    public function store(DocumentStoreRequest $request)
    {
        DB::transaction(function () use ($request) {

            $uploadedFiles = $request->file('documents');

            foreach ($uploadedFiles as $file) {
                if ($file instanceof UploadedFile) {
                    $this->documentService->store(
                        $file,
                        $request->uuid()
                    );
                }
            }
        });

        return response()->json([
            'message' => 'Document stored successfully',
        ], 201);
    }

    public function allDocuments(UuidLeadRequest $request)
    {
        [$documents, $document_types] = $this->documentService->allDocuments($request->uuid());

        $resources = $documents->map(function ($document) use ($document_types) {
            return new DocumentResource($document, $document_types);
        });

        return response()->json([
            'message' => 'Document by lead id',
            'data' => [
                'documents' => $resources,
            ]
        ]);
    }

    public function updateType(DocumentUpdateType $request)
    {
        DB::transaction(function () use ($request) {
            $document_type_id = GenericId::fromId($request->document_type_id);
            $document_uuid = $request->document_uuid();

            $this->documentService->updateType($request->lead_uuid(), $document_uuid, $document_type_id);
        });

        return response()->json([
            'message' => 'Document updated'
        ]);
    }

    public function deleteDocument(UuidDocumentRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->documentService->deleteDocument($request->uuid());
        });

        return response()->json([
            'message' => 'Document deleted'
        ]);
    }
}
