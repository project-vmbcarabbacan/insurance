<?php

namespace App\Modules\Lead\Application\Services;

use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Lead\Domain\Contracts\LeadRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadEntity;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Exceptions\InsuranceProductNotFoundException;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;

class LeadService
{

    protected function __construct(
        protected LeadRepositoryContract $lead_repository_contract
    ) {}

    public function createLead(CreateLeadDto $createLeadDto)
    {
        $product = get_product_by_code($createLeadDto->code->value());
        if (! $product) {
            throw new InsuranceProductNotFoundException();
        }

        $leadEntity = new LeadEntity(
            code: $createLeadDto->code,
            source: $createLeadDto->source,
            status: $createLeadDto->status,
            assigned_agent_id: $createLeadDto->assigned_agent_id
        );

        $this->lead_repository_contract->addLead($leadEntity);
    }

    public function updateLead(Uuid $uuid, LeadStatus $leadStatus)
    {
        $this->lead_repository_contract->updateLeadStatus($uuid, $leadStatus);
    }

    public function getLeadsByCustomerId(GenericId $customerId)
    {
        return $this->lead_repository_contract->findByCustomerId($customerId);
    }

    public function getLeadByUuid(Uuid $uuid)
    {
        return $this->lead_repository_contract->findByUuid($uuid);
    }
}
