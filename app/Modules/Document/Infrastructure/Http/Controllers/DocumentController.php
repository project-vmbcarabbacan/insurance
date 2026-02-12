<?php

namespace App\Modules\Document\Infrastructure\Http\Controllers;

use App\Modules\Document\Application\Services\DocumentService;
use App\Modules\Document\Infrastructure\Http\Requests\DocumentStoreRequest;
use App\Modules\Lead\Application\Exceptions\LeadUuidNotFoundException;
use App\Modules\Lead\Application\Services\LeadService;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DocumentController
{
    public function store(DocumentStoreRequest $request, LeadService $leadService, DocumentService $documentService)
    {
        DB::transaction(function () use ($request, $leadService, $documentService) {
            $lead = $leadService->getLeadByUuid(Uuid::fromString($request->lead_uuid));
            if (!$lead) throw new LeadUuidNotFoundException();

            $uploadedFiles = $request->file('documents');

            foreach ($uploadedFiles as $file) {
                if ($file instanceof UploadedFile) {
                    $documentService->store(
                        $file,
                        $lead
                    );
                }
            }
        });

        return response()->json([
            'message' => 'Document stored successfully'
        ], 201);
    }
}
