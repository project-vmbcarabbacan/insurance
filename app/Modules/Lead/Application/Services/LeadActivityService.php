<?php

namespace App\Modules\Lead\Application\Services;

use App\Modules\Lead\Application\DTOs\LeadActivityDto;
use App\Modules\Lead\Domain\Contracts\LeadActivityRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadActivityEntity;
use App\Shared\Domain\ValueObjects\GenericId;

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
            notes: $leadActivityDto->notes
        );

        $this->lead_activity_repository_contract->addLeadActivity($leadActivityEntity);
    }

    public function getAllActivityByLeadId(GenericId $leadId)
    {
        return $this->lead_activity_repository_contract->getAllLeadActivityByLeadId($leadId);
    }
}
