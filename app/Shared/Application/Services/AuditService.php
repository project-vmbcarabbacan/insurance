<?php

namespace App\Shared\Application\Services;

use App\Modules\Lead\Application\Services\LeadService;
use App\Shared\Application\Exceptions\AuditException;
use App\Shared\Domain\Contracts\AuditRepositoryContract;
use App\Shared\Domain\Enums\MorphType;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;

class AuditService
{
    public function __construct(
        protected AuditRepositoryContract $audit_repository_contract,
        protected LeadService $lead_service
    ) {}

    public function getAudits(MorphType $morph, Uuid $uuid, ?int $perPage = 25)
    {
        $auditableId = GenericId::fromId($this->getId($morph, $uuid));
        return $this->audit_repository_contract->getAudit($morph, $auditableId, $perPage);
    }

    private function getId(MorphType $morph, Uuid $uuid)
    {
        $module =  match ($morph) {
            MorphType::LEAD => $this->lead_service->getLeadByUuid($uuid),
            default => null
        };

        if (!$module) throw new AuditException();

        return $module->id;
    }
}
