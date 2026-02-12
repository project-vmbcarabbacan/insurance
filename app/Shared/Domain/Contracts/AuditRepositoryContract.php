<?php

namespace App\Shared\Domain\Contracts;

use App\Shared\Domain\Enums\MorphType;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Pagination\LengthAwarePaginator;

interface AuditRepositoryContract
{
    public function getAudit(MorphType $morph, GenericId $auditableId, int $perPage): ?LengthAwarePaginator;
}
