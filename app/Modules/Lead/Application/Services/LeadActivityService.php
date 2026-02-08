<?php

namespace App\Modules\Lead\Application\Services;

use App\Models\User;
use App\Modules\Lead\Application\DTOs\LeadActivityDto;
use App\Modules\Lead\Domain\Contracts\LeadActivityRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadActivityEntity;
use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadActivityService
{
    public function __construct(
        protected LeadActivityRepositoryContract $lead_activity_repository_contract
    ) {}

    public function addLeadActivity(LeadActivityDto $leadActivityDto)
    {
        $leadActivityEntity = new LeadActivityEntity(
            lead_id: $leadActivityDto->lead_id,
            type: $leadActivityDto->type,
            notes: $leadActivityDto->notes,
            performed_by_name: $leadActivityDto->performed_by_name,
            performed_by_id: $leadActivityDto->performed_by_id
        );

        $this->lead_activity_repository_contract->addLeadActivity($leadActivityEntity);
    }

    public function generateLeadDto(GenericId $leadId, LeadActivityType $leadActivityType, ?User $user = null, ?string $notes = '')
    {
        $performedBy = $user?->name ?? 'System';

        return new LeadActivityDto(
            lead_id: $leadId,
            type: $leadActivityType,
            notes: $notes,
            performed_by_name: LowerText::fromString($performedBy),
            performed_by_id: $user ? GenericId::fromId($user?->id) : null
        );
    }

    public function getAllActivityByLeadId(GenericId $leadId)
    {
        return $this->lead_activity_repository_contract->getAllLeadActivityByLeadId($leadId);
    }
}
