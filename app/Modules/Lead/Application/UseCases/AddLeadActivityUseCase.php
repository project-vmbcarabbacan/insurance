<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Lead;
use App\Modules\Lead\Application\DTOs\LeadActivityDto;
use App\Modules\Lead\Application\Services\LeadActivityService;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Domain\Maps\LeadActivityResponseDueDateMap;
use App\Modules\Lead\Domain\Maps\LeadTransitionMap;
use App\Shared\Domain\Enums\LeadActivityResponse;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\Uuid;

class AddLeadActivityUseCase
{
    public function __construct(
        protected LeadService $lead_service,
        protected LeadActivityService $lead_activity_service
    ) {}

    public function execute(Uuid $uuid, LeadActivityDto $leadActivityDto, LeadActivityResponse $leadActivityResponse)
    {
        $leadStatus = LeadTransitionMap::transition($leadActivityResponse);
        $dues = LeadActivityResponseDueDateMap::dueIn($leadActivityResponse);
        $dueAt = $dues ? now()->add($dues) : null;

        $this->lead_activity_service->addLeadActivity($leadActivityDto);
        $this->lead_service->updateLead($uuid, $leadStatus);
        $this->lead_service->updateDueDate($uuid, $dueAt ? GenericDate::fromString($dueAt) : null);
    }
}
