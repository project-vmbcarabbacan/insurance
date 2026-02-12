<?php

namespace App\Shared\Infrastructure\Repositories;

use App\Models\AuditLog;
use App\Shared\Domain\Contracts\AuditRepositoryContract;
use App\Shared\Domain\Enums\MorphType;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditRepository implements AuditRepositoryContract
{
    public function getAudit(MorphType $morph, GenericId $auditableId, ?int $perPage = 25): ?LengthAwarePaginator
    {
        return AuditLog::morphType($morph)
            ->morphId($auditableId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
