<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Shared\Application\Services\AuditService;
use App\Shared\Domain\Enums\MorphType;
use App\Shared\Domain\ValueObjects\Uuid;
use App\Shared\Infrastructure\Http\Requests\AuditRequest;
use App\Shared\Infrastructure\Http\Resources\AuditResource;

class AuditController
{
    public function index(AuditRequest $request, AuditService $auditService)
    {
        $morph = MorphType::tryFrom($request->morph);
        $uuid = Uuid::fromString($request->uuid);

        $audits = $auditService->getAudits($morph, $uuid, 15);
        $audits->through(fn($audit) => new AuditResource($audit));


        return response()->json([
            'message' => 'Audits',
            'data' => $audits
        ]);
    }
}
