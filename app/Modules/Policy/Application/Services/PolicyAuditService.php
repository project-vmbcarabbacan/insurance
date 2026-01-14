<?php

namespace App\Modules\Policy\Application\Services;

use App\Modules\Policy\Domain\Audits\PolicyAuditTransitionMap;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\PolicyStatus;

final class PolicyAuditService
{
    public function auditStatusChange(
        mixed $policy,
        AuditAction $action,
        ?PolicyStatus $oldStatus,
        PolicyStatus $newStatus
    ): void {
        PolicyAuditTransitionMap::validate(
            action: $action,
            from: $oldStatus,
            to: $newStatus
        );

        insurance_audit(
            $policy,
            $action,
            ['status' => $oldStatus?->value],
            ['status' => $newStatus->value]
        );
    }
}
