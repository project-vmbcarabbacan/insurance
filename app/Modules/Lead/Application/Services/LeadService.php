<?php

namespace App\Modules\Lead\Application\Services;

use App\Models\User;
use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Lead\Domain\Contracts\LeadRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadEntity;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Exceptions\InsuranceProductNotFoundException;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Query\Builder;

class LeadService
{

    public function __construct(
        protected LeadRepositoryContract $leadRepositoryContract
    ) {}

    public function createLead(CreateLeadDto $createLeadDto)
    {
        $leadEntity = new LeadEntity(
            code: $createLeadDto->code,
            source: $createLeadDto->source,
            status: $createLeadDto->status,
            customer_id: $createLeadDto->customer_id,
            due_date: $createLeadDto->due_date,
            assigned_agent_id: $createLeadDto->assigned_agent_id
        );

        return $this->leadRepositoryContract->addLead($leadEntity);
    }

    public function agentAssignment(GenericId $leadId, User $user)
    {
        $this->leadRepositoryContract->assignAgentId($leadId, $user);
    }

    public function updateLead(Uuid $uuid, LeadStatus $leadStatus)
    {
        $this->leadRepositoryContract->updateLeadStatus($uuid, $leadStatus);
    }

    public function updateDueDate(Uuid $uuid, ?GenericDate $dueDate)
    {
        $this->leadRepositoryContract->updateLeadDueDate($uuid, $dueDate);
    }

    public function getLeadsByCustomerId(GenericId $customerId, ?LowerText $keyword = null, int $per_page = 4)
    {
        return $this->leadRepositoryContract->findByCustomerId($customerId, $keyword, $per_page);
    }

    public function getLeadByUuid(Uuid $uuid)
    {
        return $this->leadRepositoryContract->findByUuid($uuid);
    }

    public function getLeadByIdd(GenericId $id)
    {
        return $this->leadRepositoryContract->findLeadById($id);
    }

    public function activeLead(GenericId $customerId, LeadProductType $code, Builder $pivot, ?array $conditions = [])
    {
        return $this->leadRepositoryContract->activeLead($customerId, $code, $pivot, $conditions);
    }
}
